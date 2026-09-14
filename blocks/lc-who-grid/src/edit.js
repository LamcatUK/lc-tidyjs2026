import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import RepeaterField from '../../_shared/RepeaterField';

const ITEM_FIELDS = [
	{ name: 'icon', label: 'Icon', type: 'image', mimeTypes: [ 'image/svg+xml' ] },
	{ name: 'title', label: 'Title', type: 'text' },
	{ name: 'description', label: 'Description', type: 'text' },
	{ name: 'link', label: 'Link', type: 'link' },
];

const EMPTY_ITEM = {
	icon: 0,
	iconUrl: '',
	title: '',
	description: '',
	link: '',
	linkText: '',
};

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, items, outro } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Who Grid" textDomain="lc-tidyjs2026">
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
				label={ __( 'Items', 'lc-tidyjs2026' ) }
				value={ items }
				onChange={ ( rows ) => setAttributes( { items: rows } ) }
				fields={ ITEM_FIELDS }
				emptyRow={ EMPTY_ITEM }
				layout="column"
			/>
			<TextControl
				label={ __( 'Outro', 'lc-tidyjs2026' ) }
				value={ outro }
				onChange={ ( value ) => setAttributes( { outro: value } ) }
			/>
		</EditorBlockShell>
	);
}
