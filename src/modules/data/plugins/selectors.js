/**
 * External dependencies
 */
import { curry, includes } from 'lodash';

export const getPlugins = ( state ) => state.plugins;

export const hasPlugin = curry( ( state, plugin ) => includes( getPlugins( state ), plugin ) );
