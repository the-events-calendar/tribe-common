import { reducer } from './reducer';
import { default as actions } from './actions';
import * as selectors from './selectors';
import { default as resolvers } from './resolvers';

export const STORE_NAME = 'tec/classy';

export const storeConfig = {
	reducer,
	actions,
	selectors,
	resolvers,
};
