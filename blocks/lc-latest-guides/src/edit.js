import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import PostTypePicker from '../../_shared/PostTypePicker';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { pinnedId } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Latest Guides" textDomain="lc-tidyjs2026">
			<p>{ __( 'Shows 3 guides in a slider: the latest posts, automatically.', 'lc-tidyjs2026' ) }</p>
			<PostTypePicker
				label={ __( 'Pinned guide', 'lc-tidyjs2026' ) }
				postType="post"
				value={ pinnedId }
				onChange={ ( id ) => setAttributes( { pinnedId: id } ) }
				help={ __( 'Optional. Shown first, followed by the latest guides.', 'lc-tidyjs2026' ) }
			/>
		</EditorBlockShell>
	);
}
