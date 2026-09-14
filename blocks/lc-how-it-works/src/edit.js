import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, step1Title, step1Content, step2Title, step2Content, step3Title, step3Content, highlight } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC How It Works" textDomain="lc-tidyjs2026">
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
			<TextControl
				label={ __( 'Step 1 Title', 'lc-tidyjs2026' ) }
				value={ step1Title }
				onChange={ ( value ) => setAttributes( { step1Title: value } ) }
			/>
			<TextControl
				label={ __( 'Step 1 Content', 'lc-tidyjs2026' ) }
				value={ step1Content }
				onChange={ ( value ) => setAttributes( { step1Content: value } ) }
			/>
			<TextControl
				label={ __( 'Step 2 Title', 'lc-tidyjs2026' ) }
				value={ step2Title }
				onChange={ ( value ) => setAttributes( { step2Title: value } ) }
			/>
			<TextControl
				label={ __( 'Step 2 Content', 'lc-tidyjs2026' ) }
				value={ step2Content }
				onChange={ ( value ) => setAttributes( { step2Content: value } ) }
			/>
			<TextControl
				label={ __( 'Step 3 Title', 'lc-tidyjs2026' ) }
				value={ step3Title }
				onChange={ ( value ) => setAttributes( { step3Title: value } ) }
			/>
			<TextControl
				label={ __( 'Step 3 Content', 'lc-tidyjs2026' ) }
				value={ step3Content }
				onChange={ ( value ) => setAttributes( { step3Content: value } ) }
			/>
			<TextControl
				label={ __( 'Highlight', 'lc-tidyjs2026' ) }
				value={ highlight }
				onChange={ ( value ) => setAttributes( { highlight: value } ) }
			/>
		</EditorBlockShell>
	);
}
