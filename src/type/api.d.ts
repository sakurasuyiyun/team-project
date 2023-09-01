interface IResponse<T> {
    code: number;
    data: T,
    message: string;
}

type RSP<T> = Promise<IResponse<T>>;