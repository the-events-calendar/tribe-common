/**
 * External Dependencies
 */
import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';
import { isFunction } from 'lodash';

export default class EventCatcher extends PureComponent {
	static propTypes = {
		children: PropTypes.node.isRequired,
		onChange: PropTypes.func,
		onClick: PropTypes.func,
		onFocusIn: PropTypes.func,
		onFocusOut: PropTypes.func,
	}

	container = React.createRef();

	componentDidMount() {
		this.listeners.forEach( ( [ event, listener ] ) => (
			this.container.current.addEventListener( event, listener )
		) );
	}

	componentWillUnmount() {
		this.listeners.forEach( ( [ event, listener ] ) => (
			this.container.current.removeEventListener( event, listener )
		) );
	}

	get listeners() {
		return Object.entries( {
			change: this.props.onChange,
			click: this.props.onClick,
			focusin: this.props.onFocusIn,
			focusout: this.props.onFocusOut,
		} ).filter( ( [ _, listener ] ) => isFunction( listener ) );
	}

	render() {
		return (
			<div ref={ this.container }>
				{this.props.children}
			</div>
		);
	}
}
