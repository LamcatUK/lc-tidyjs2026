import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { content } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Social Proof" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Content', 'lc-tidyjs2026' ) }
				value={ content }
				onChange={ ( value ) => setAttributes( { content: value } ) }
			/>
		</EditorBlockShell>
	);
}
