import axios from 'axios';

export const toAbsoluteUrl = ( pathname ) => process.env.PUBLIC_URL + pathname;

export const baseUrl = '';

/*
 * Axios instance to used to fetch content.
 *
 * @since TBD
 */
export const axiosInstance = axios.create({
	baseURL: baseUrl,
});
