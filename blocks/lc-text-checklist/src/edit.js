import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, checklist, outro } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Text Checklist" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Title', 'lc-tidyjs2026' ) }
				value={ title }
				onChange={ ( value ) => setAttributes( { title: value } ) }
			/>
			<TextareaControl
				label={ __( 'Intro', 'lc-tidyjs2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
			/>
			<TextareaControl
				label={ __( 'Checklist', 'lc-tidyjs2026' ) }
				value={ checklist }
				onChange={ ( value ) => setAttributes( { checklist: value } ) }
				help={ __( 'One item per line', 'lc-tidyjs2026' ) }
			/>
			<TextareaControl
				label={ __( 'Outro', 'lc-tidyjs2026' ) }
				value={ outro }
				onChange={ ( value ) => setAttributes( { outro: value } ) }
			/>
		</EditorBlockShell>
	);
}
