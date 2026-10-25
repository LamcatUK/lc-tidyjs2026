import { __ } from '@wordpress/i18n';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useEffect, useMemo, useRef, useState } from '@wordpress/element';

/**
 * Wraps a block's edit.js output in a collapsible section with a title bar
 * and open/closed toggle, persisting the toggle state per block-instance
 * (keyed by the block's position path within the post plus the current
 * URL) in localStorage — so a page with many blocks stays scannable in the
 * editor instead of every block's full field set staying expanded at once.
 *
 * @param {Object}   props
 * @param {Object}   props.blockProps       Result of useBlockProps().
 * @param {string}   props.clientId         The block's clientId, for deriving its position path.
 * @param {string}   [props.classPrefix]    Class/prefix root, matches editor.css.
 * @param {string}   [props.textDomain]     i18n text domain for the toggle's aria-label.
 * @param {string}   [props.storageNamespace] Extra localStorage key segment, in case two shells need independent state on the same path.
 * @param {string}   props.title            Block name shown in the title bar.
 * @param {boolean}  [props.defaultOpen]    Initial state before localStorage is read.
 * @param {*}        props.children         The block's own field controls.
 */
/**
 * Keep Shift+Arrow inside the field being edited. Gutenberg's writing flow
 * listens for keydown on an ancestor and, when the caret is at the edge of a
 * field, turns Shift+Arrow into a block-level multi-selection — so extending
 * a text selection in one of this block's inputs jumps to selecting the whole
 * block. Stopping the event before it reaches the writing flow means the
 * browser's native text selection handles it.
 *
 * This has to be a native listener, not React's onKeyDown: the writing flow
 * listens natively on an element below React's root, so by the time a React
 * handler runs (React listens at the root) it has already seen the event.
 *
 * @param {KeyboardEvent} event Native keydown event.
 */
function keepShiftArrowInField( event ) {
	if ( event.shiftKey && ! event.ctrlKey && ! event.metaKey && event.key.startsWith( 'Arrow' ) ) {
		event.stopPropagation();
	}
}

export default function EditorBlockShell( {
	blockProps,
	clientId,
	classPrefix = 'lc-tidyjs2026',
	textDomain = 'lc-tidyjs2026',
	storageNamespace = 'block',
	title,
	children,
	defaultOpen = true,
} ) {
	const [ isOpen, setIsOpen ] = useState( defaultOpen );
	const contentRef = useRef( null );
	// Not useInstanceId(): every block's bundle carries its own copy of this
	// component, each with its own counter, so ids collided across block types.
	const contentId = `${ classPrefix }-editor-block-content-${ clientId }`;
	const blockPath = useSelect(
		( select ) => {
			if ( ! clientId ) {
				return '';
			}

			const { getBlockIndex, getBlockRootClientId } = select( blockEditorStore );
			const path = [];
			let currentId = clientId;

			while ( currentId ) {
				const parentId = getBlockRootClientId( currentId ) || '';
				path.unshift( String( getBlockIndex( currentId, parentId ) ) );
				currentId = parentId || null;
			}

			return path.join( '.' );
		},
		[ clientId ]
	);
	const storageKey = useMemo( () => {
		if ( ! clientId || ! blockPath || typeof window === 'undefined' ) {
			return '';
		}

		return [ classPrefix, 'editor-block-state', storageNamespace, window.location.pathname, window.location.search, blockPath ].join(
			':'
		);
	}, [ blockPath, classPrefix, clientId, storageNamespace ] );

	useEffect( () => {
		if ( ! storageKey || typeof window === 'undefined' ) {
			return;
		}

		const storedValue = window.localStorage.getItem( storageKey );

		if ( storedValue === 'closed' ) {
			setIsOpen( false );
		} else if ( storedValue === 'open' ) {
			setIsOpen( true );
		}
	}, [ storageKey ] );

	useEffect( () => {
		if ( ! storageKey || typeof window === 'undefined' ) {
			return;
		}

		window.localStorage.setItem( storageKey, isOpen ? 'open' : 'closed' );
	}, [ isOpen, storageKey ] );

	useEffect( () => {
		const content = contentRef.current;

		if ( ! content ) {
			return undefined;
		}

		content.addEventListener( 'keydown', keepShiftArrowInField );

		return () => content.removeEventListener( 'keydown', keepShiftArrowInField );
	}, [] );

	return (
		<div { ...blockProps }>
			<div className={ `${ classPrefix }-editor-block__title` }>
				<span>{ title }</span>
				<button
					type="button"
					className={ `${ classPrefix }-editor-block__toggle` }
					onClick={ () => setIsOpen( ( open ) => ! open ) }
					aria-expanded={ isOpen }
					aria-controls={ contentId }
					aria-label={ isOpen ? __( 'Hide block fields', textDomain ) : __( 'Show block fields', textDomain ) }
				>
					<span aria-hidden="true">{ isOpen ? '−' : '+' }</span>
				</button>
			</div>
			<div id={ contentId } className={ `${ classPrefix }-editor-block__content` } hidden={ ! isOpen } ref={ contentRef }>
				{ children }
			</div>
		</div>
	);
}
