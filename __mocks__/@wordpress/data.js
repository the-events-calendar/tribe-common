/**
 * External dependencies
 */
import { noop } from 'lodash';

export const select = noop;
export const withSelect = () => ( component ) => component;
export const withDispatch = () => ( component ) => component;
