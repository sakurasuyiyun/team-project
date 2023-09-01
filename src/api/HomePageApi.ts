import http from "@/utils/HomePageRequest"
export const getData = (): RSP<IHomeData> => http.get('/admin/stat')

