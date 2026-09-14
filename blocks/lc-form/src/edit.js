import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { contactFormId } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Form" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Contact Form ID', 'lc-tidyjs2026' ) }
				value={ contactFormId }
				onChange={ ( value ) => setAttributes( { contactFormId: value } ) }
			/>
		</EditorBlockShell>
	);
}
