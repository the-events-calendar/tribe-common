import { CurrencyPosition } from './CurrencyPosition';

export type Currency = {
	code: string;
	label?: string;
	position: CurrencyPosition;
	symbol: string;
}
