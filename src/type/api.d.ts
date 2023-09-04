interface IResponse<T> {
    code: number;
    data: T,
    message: string;
}

type RSP<T> = Promise<IResponse<T>>;

interface IRequest<T> {
    config: object
    data: T
    headers: object
    request: XMLHttpRequest
    status: number
    statusText: string
}

type RQ<T,K> = Promise<IRequest<T,K>>;