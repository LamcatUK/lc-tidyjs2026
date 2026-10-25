import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import PostTypePicker from '../../_shared/PostTypePicker';

const SLOTS = [ 0, 1, 2 ];

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { pinnedId, pinnedIds } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	// pinnedId is the earlier single-pin attribute: it still fills slot 1 until
	// the block is next edited, which writes everything to pinnedIds.
	const slots = SLOTS.map( ( i ) => ( pinnedIds && pinnedIds[ i ] ) || ( 0 === i && ! ( pinnedIds && pinnedIds.length ) ? pinnedId : 0 ) || 0 );

	function setSlot( index, id ) {
		const next = [ ...slots ];
		next[ index ] = id || 0;
		setAttributes( { pinnedIds: next, pinnedId: 0 } );
	}

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Latest Guides" textDomain="lc-tidyjs2026">
			<p>{ __( 'Shows 3 guides in a slider: any pinned below (in order), then the latest posts to fill the rest.', 'lc-tidyjs2026' ) }</p>
			{ SLOTS.map( ( i ) => (
				<PostTypePicker
					key={ i }
					label={ `${ __( 'Pinned guide', 'lc-tidyjs2026' ) } ${ i + 1 }` }
					postType="post"
					value={ slots[ i ] }
					onChange={ ( id ) => setSlot( i, id ) }
					help={ 0 === i ? __( 'Optional. Leave empty to use the latest guides.', 'lc-tidyjs2026' ) : undefined }
				/>
			) ) }
		</EditorBlockShell>
	);
}
