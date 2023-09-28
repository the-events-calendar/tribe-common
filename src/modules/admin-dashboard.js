/**
 * This represents the entry point for the admin dashboard.
 */
const { createElement } = wp.element;

const DashBoard = () => {
    return(
        <h2>
            Hello from the admin dashboard!
        </h2>
    );
};

const root = document.getElementById( 'tec-events-dashboard' );

wp.element.render( createElement( DashBoard ), root );
