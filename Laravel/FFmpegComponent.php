<?php

namespace App\Components\Video;

use App\Dto\GifWidthHeightDto;
use Carbon\Carbon;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Format\Video\X264;
use FFMpeg\Media\Video;
use Illuminate\Support\Facades\File;

class FFmpegComponent
{
    private FFmpeg $ffmpeg;
    private string $path;

    public function __construct()
    {
        $this->ffmpeg = FFmpeg::create([
            'temporary_directory' => config('ffmpeg.tmp_dir'),
        ]);

        $this->path = config('ffmpeg.files_path');
    }

    public function convertToMp4(Video $video): string
    {
        $fileName = $this->makeVideoName($video->getPathfile());

        $fullPath = $this->path . $fileName;

        $format = new X264();

        $format->setVideoCodec('libx264');

        $format->setKiloBitrate(1000);

        $video->save($format, $fullPath);

        return $fullPath;
    }

    public function getVideoByPath(string $path): Video
    {
        return $this->ffmpeg->open($path);
    }

    public function getPartOfVideo(Video $video, int $start, int $duration): Video
    {
        $video = clone $video;

        $resultVideo = $video
            ->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($duration));

        return $resultVideo;
    }

    public function scaleVideo(Video $video, int $width, int $height): Video
    {
        $video = clone $video;

        $video
            ->filters()
            ->resize(new Dimension($width, $height))
            ->synchronize();

        return $video;
    }

    public function addOverlayToVideo(Video $video, string $imagePath, int $bottom, int $right): Video
    {
        $video = clone $video;

        $video
            ->filters()
            ->watermark($imagePath, [
                'position' => 'relative',
                'bottom' => $bottom,
                'right' => $right,
            ]);

        return $video;
    }

    public function addTextToVideo(Video $video, string $fontPath, string $text, int $x, int $y): Video
    {
        $video = clone $video;

        $video
            ->filters()
            ->custom("drawtext=fontfile=$fontPath:text='$text':fontcolor=white:fontsize=24:x=$x:y=$y");

        return $video;
    }

    public function getGifPathByVideo(Video $video, int $width, int $height): string
    {
        $fileName = $this->makeVideoName($video->getPathfile(), 'gif');

        $fullPath = $this->path . $fileName;

        shell_exec("ffmpeg -t 4 -i {$video->getPathfile()} -vf \"fps=20\" -loop 0 $fullPath
");

        return $fullPath;
    }

    public function getVideoDuration(Video $video): int
    {
        $fileName = $this->makeVideoName($video->getPathfile(), $this->getVideoTypeByPath($video->getPathfile()));
        $fullPath = $this->path . $fileName;

        shell_exec("ffmpeg -i {$video->getPathfile()} -vcodec copy -acodec copy $fullPath");

        $duration = shell_exec("ffprobe -i $fullPath -show_entries format=duration -v quiet -of csv=\"p=0\" 2>&1");

        $duration = (int)$duration;

        File::delete($fullPath);

        return $duration;
    }

    public function getGifSizeByPath(string $gifPath): ?GifWidthHeightDto
    {
        $ffprobe = FFProbe::create();

        $videoDimension = $ffprobe
            ->streams($gifPath)
            ->videos()
            ->first()
            ?->getDimensions();

        if ($videoDimension === null) {
            return null;
        }

        $width = $videoDimension->getWidth();
        $height = $videoDimension->getHeight();

        return new GifWidthHeightDto($width, $height);
    }

    private function makeVideoName(string $path, string $format = 'mp4'): string
    {
        return md5($path . ((string) Carbon::now()->timestamp)) . ".$format";
    }

    private function getVideoTypeByPath(string $path): string
    {
        $n = strrpos($path, '.');
        return ($n === false) ? '' : substr($path, $n+1);
    }
}
