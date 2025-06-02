/**
 * Extend the global to let TypeScript know about the global window object.
 */
declare global {
	interface Window {
		wp?: {
			data?: {
				hello: Function;
				select: Function;
				dispatch: Function;
				subscribe: Function;
			};
			oldEditor?: {
				initialize: Function;
				getContent: Function;
				remove: Function;
			};
		};
		tinymce?: {
			get: ( id: string ) => {
				initialized: boolean;
				on: Function;
				off: Function;
			};
		};
	}
}

export {};
