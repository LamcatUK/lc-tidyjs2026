import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
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
			<div className="lc-tidyjs2026-editor-field">
				<label className="lc-tidyjs2026-editor-field__label">{ __( 'Checklist', 'lc-tidyjs2026' ) }</label>
				<RichText
					tagName="div"
					identifier="checklist"
					className="lc-tidyjs2026-editor-field__control"
					aria-label={ __( 'Checklist', 'lc-tidyjs2026' ) }
					placeholder={ __( 'Checklist', 'lc-tidyjs2026' ) }
					allowedFormats={ [ 'core/link', 'core/bold', 'core/italic' ] }
					// Items saved before this was rich text are newline-separated.
					value={ ( checklist || '' ).replace( /\r\n|\r|\n/g, '<br>' ) }
					onChange={ ( value ) => setAttributes( { checklist: value } ) }
				/>
				<p className="lc-tidyjs2026-editor-field__help">{ __( 'One item per line (Enter). Select text to add a link.', 'lc-tidyjs2026' ) }</p>
			</div>
			<TextareaControl
				label={ __( 'Outro', 'lc-tidyjs2026' ) }
				value={ outro }
				onChange={ ( value ) => setAttributes( { outro: value } ) }
			/>
		</EditorBlockShell>
	);
}
