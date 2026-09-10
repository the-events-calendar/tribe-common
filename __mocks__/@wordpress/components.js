/**
 * External dependencies
 */
import React from 'react';
import { noop } from 'lodash';

export const withAPIData = () => noop;
export const Spinner = () => "🏃‍♂️";
export const Modal = ( { title, children } ) => (
	<div>
		<span>{ title }</span>
		<span>{ children }</span>
	</div>
);
export const Dashicon = ( { className, icon } ) => <span className={ className }>{ icon }</span>;
export const Dropdown = () => <span>Dropdown</span>;
export const Tooltip = () => <span>Tooltip</span>;
export const PanelBody = ({ children }) => <span className="PanelBody">{ children }</span>
export const Popover = ( { children, className, anchor, focusOnMount, noArrow, onClose } ) => (
	<div className={ className } data-testid="popover">
		{ children }
	</div>
);
Popover.Slot = () => <span>popover-slot</span>;
