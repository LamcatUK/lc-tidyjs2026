import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, step1Title, step1Subtitle, step1Content, step2Title, step2Subtitle, step2Content, step3Title, step3Subtitle, step3Content, highlight } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC How Stack" textDomain="lc-tidyjs2026">
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
				label={ __( 'Step 1 Subtitle', 'lc-tidyjs2026' ) }
				value={ step1Subtitle }
				onChange={ ( value ) => setAttributes( { step1Subtitle: value } ) }
			/>
			<TextareaControl
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
				label={ __( 'Step 2 Subtitle', 'lc-tidyjs2026' ) }
				value={ step2Subtitle }
				onChange={ ( value ) => setAttributes( { step2Subtitle: value } ) }
			/>
			<TextareaControl
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
				label={ __( 'Step 3 Subtitle', 'lc-tidyjs2026' ) }
				value={ step3Subtitle }
				onChange={ ( value ) => setAttributes( { step3Subtitle: value } ) }
			/>
			<TextareaControl
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
