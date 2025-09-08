export type CurrencyPosition = 'prefix' | 'postfix';

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

export type CurrencyParams = Omit< Currency, 'label' | 'code' > & {
	/**
	 * The character used to separate the integer part from the fractional part of the currency.
	 */
	decimalSeparator?: string;

	/**
	 * The character used to separate thousands in the integer part of the currency.
	 */
	thousandSeparator?: string;

	/**
	 * The number of decimal places to display.
	 */
	precision: number;
};

export type FormatCurrencyParams = Partial< CurrencyParams > & {
	value: string;
};
