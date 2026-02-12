export default interface APIResult<T> {
    data?: T;
    error?: string;
}