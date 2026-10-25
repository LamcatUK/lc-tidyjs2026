import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, CheckboxControl, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { decodeEntities } from '@wordpress/html-entities';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { heading, pages } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	// The service pages: children of the top-level /services/ page.
	const { services, loading } = useSelect( ( select ) => {
		const core = select( coreStore );
		const parents = core.getEntityRecords( 'postType', 'page', { slug: 'services', parent: 0, per_page: 1 } );
		const parent = parents && parents[ 0 ];
		const children = parent
			? core.getEntityRecords( 'postType', 'page', {
					parent: parent.id,
					per_page: -1,
					orderby: 'menu_order',
					order: 'asc',
					status: 'publish',
			  } )
			: null;

		return {
			services: children || [],
			loading: null === parents || ( parent && null === children ),
		};
	}, [] );

	function toggle( id, checked ) {
		const next = checked ? [ ...( pages || [] ), id ] : ( pages || [] ).filter( ( p ) => p !== id );
		setAttributes( { pages: next } );
	}

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Related Services" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Heading', 'lc-tidyjs2026' ) }
				value={ heading }
				onChange={ ( value ) => setAttributes( { heading: value } ) }
				help={ __( 'Defaults to "Related Services" if left blank.', 'lc-tidyjs2026' ) }
			/>
			<div className="lc-tidyjs2026-editor-field">
				<label className="lc-tidyjs2026-editor-field__label">{ __( 'Services', 'lc-tidyjs2026' ) }</label>
				{ loading && <Spinner /> }
				{ ! loading && 0 === services.length && (
					<p className="lc-tidyjs2026-editor-field__help">{ __( 'No pages found under /services/.', 'lc-tidyjs2026' ) }</p>
				) }
				{ services.map( ( page ) => (
					<CheckboxControl
						key={ page.id }
						__nextHasNoMarginBottom
						label={ decodeEntities( page.title.rendered ) }
						checked={ ( pages || [] ).includes( page.id ) }
						onChange={ ( checked ) => toggle( page.id, checked ) }
					/>
				) ) }
				<p className="lc-tidyjs2026-editor-field__help">{ __( 'Tick the services to show. If none are ticked, the block is hidden.', 'lc-tidyjs2026' ) }</p>
			</div>
		</EditorBlockShell>
	);
}
