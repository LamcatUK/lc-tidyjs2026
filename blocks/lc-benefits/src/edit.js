import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import RepeaterField from '../../_shared/RepeaterField';

const BENEFIT_FIELDS = [
	{ name: 'icon', label: 'Icon', type: 'image', mimeTypes: [ 'image/svg+xml' ] },
	{ name: 'title', label: 'Title', type: 'text' },
	{ name: 'text', label: 'Text', type: 'textarea' },
];

const EMPTY_BENEFIT = {
	icon: 0,
	iconUrl: '',
	title: '',
	text: '',
};

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, benefits } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Benefits" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Title', 'lc-tidyjs2026' ) }
				value={ title }
				onChange={ ( value ) => setAttributes( { title: value } ) }
			/>
			<RepeaterField
				label={ __( 'Benefits', 'lc-tidyjs2026' ) }
				value={ benefits }
				onChange={ ( rows ) => setAttributes( { benefits: rows } ) }
				fields={ BENEFIT_FIELDS }
				emptyRow={ EMPTY_BENEFIT }
				layout="column"
			/>
		</EditorBlockShell>
	);
}
