import { AxiosRequestConfig } from 'axios'
import { API_CONFIG, AxiosService } from '@/api/AxiosService'

class AlbumsApi extends AxiosService {
  constructor(config?: AxiosRequestConfig) {
    super(config)
  }

  getPhotos = async (payload: { id: number }) => {
    const id = payload.id

    return await this.axiosCall<ApiAlbumItemType[]>({
      method: 'get',
      url: `/${id}/photos`,
    })
  }
}

export default new AlbumsApi(API_CONFIG)
