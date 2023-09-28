/**
 * External dependencies.
 */
import React from 'react';
import ReactDOM from 'react-dom';

const AdminDashbord = () => (
    <h2>
        Hello from the Admin Dashboard!
    </h2>
);

const root = document.getElementById( 'tec-events-dashboard' );
ReactDOM.render(<AdminDashbord />, root);
