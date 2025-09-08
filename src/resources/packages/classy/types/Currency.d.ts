import { CurrencyPosition } from './CurrencyPosition';

/**
 * The Currency settings type.
 *
 * @since TBD
 */
export type Currency = {
	/**
	 * The currency code, e.g., 'USD', 'EUR'.
	 *
	 * @since TBD
	 */
	code: string;

	/**
	 * An optional label for the currency, e.g., 'US Dollar', 'Euro'.
	 *
	 * @since TBD
	 */
	label?: string;

	/**
	 * The position of the currency symbol, e.g., 'prefix', 'suffix'.
	 *
	 * @since TBD
	 */
	position: CurrencyPosition;

	/**
	 * The currency symbol, e.g., '$', '€'.
	 *
	 * @since TBD
	 */
	symbol: string;
};
