import axios, { AxiosError, AxiosInstance, AxiosRequestConfig, AxiosResponse } from 'axios'

export type ApiResponseType<T = unknown> = T

export type ServiceResponseType<T> = Promise<[null, AxiosResponse<ApiResponseType<T>>] | [AxiosError<ApiResponseType>]>

export class AxiosService {
  private axiosInstance!: AxiosInstance

  constructor(config?: AxiosRequestConfig) {
    this.axiosInstance = axios.create(config)

    /** Request handler */
    this.axiosInstance.interceptors.request.use((config: any) => {
      return config
    })

    /** Response handler */
    this.axiosInstance.interceptors.response.use(
      (response) => {
        return Promise.resolve(response)
      },
      async (error) => {
        switch (error?.response?.status) {
          case 401: {
            break
          }

          case 403: {
            break
          }

          case 404: {
            break
          }

          case 422: {
            break
          }

          case 429: {
            break
          }

          case 500: {
            break
          }

          default: {
            break
          }
        }

        return Promise.reject(error)
      }
    )
  }

  protected async axiosCall<T = any>(config: AxiosRequestConfig): ServiceResponseType<T> {
    try {
      const response = await this.axiosInstance.request<ApiResponseType<T>>(config)

      return [null, response]
    } catch (error) {
      return [error as AxiosError<ApiResponseType>]
    }
  }
}

export const getBaseUrl = (): string => {
  return [import.meta.env.DEV ? undefined : import.meta.env.VITE_BASE_URL, '/albums'].join('')
}

export const API_CONFIG: AxiosRequestConfig = {
  baseURL: getBaseUrl(),
  withCredentials: true,
}
