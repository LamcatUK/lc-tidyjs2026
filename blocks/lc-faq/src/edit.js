import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import RepeaterField from '../../_shared/RepeaterField';

const FAQ_FIELDS = [
	{ name: 'question', label: 'Question', type: 'text' },
	{ name: 'answer', label: 'Answer', type: 'textarea' },
];

const EMPTY_FAQ_ITEM = {
	question: '',
	answer: '',
};

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, faqItems } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC FAQ" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Title', 'lc-tidyjs2026' ) }
				value={ title }
				onChange={ ( value ) => setAttributes( { title: value } ) }
			/>
			<TextControl
				label={ __( 'Intro', 'lc-tidyjs2026' ) }
				value={ intro }
				onChange={ ( value ) => setAttributes( { intro: value } ) }
			/>
			<RepeaterField
				label={ __( 'FAQ Items', 'lc-tidyjs2026' ) }
				value={ faqItems }
				onChange={ ( rows ) => setAttributes( { faqItems: rows } ) }
				fields={ FAQ_FIELDS }
				emptyRow={ EMPTY_FAQ_ITEM }
				layout="column"
			/>
		</EditorBlockShell>
	);
}
