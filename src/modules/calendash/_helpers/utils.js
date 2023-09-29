import axios from 'axios';

/**
 * Base URL for the API.
 * 
 * @since TBD
 * 
 * @type {string}
 */
export const baseUrl = '';

/*
 * An instance of Axios with predefined settings.
 *
 * @since TBD
 * 
 * @see https://axios-http.com/docs/instance
 */
export const axiosInstance = axios.create({
	baseURL: baseUrl,
});
