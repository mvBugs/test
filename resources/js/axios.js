import axios from 'axios';

export const HTTP = axios.create({
    baseURL: process.env.MIX_APP_REST_API
})
