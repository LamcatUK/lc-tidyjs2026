import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { ctaTitle, content } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC CTA" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'CTA Title', 'lc-tidyjs2026' ) }
				value={ ctaTitle }
				onChange={ ( value ) => setAttributes( { ctaTitle: value } ) }
			/>
			<TextareaControl
				label={ __( 'Content', 'lc-tidyjs2026' ) }
				value={ content }
				onChange={ ( value ) => setAttributes( { content: value } ) }
			/>
		</EditorBlockShell>
	);
}
