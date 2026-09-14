import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl, TextareaControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import RepeaterField from '../../_shared/RepeaterField';

const SERVICE_FIELDS = [
	{ name: 'icon', label: 'Icon', type: 'image', mimeTypes: [ 'image/svg+xml' ] },
	{ name: 'title', label: 'Title', type: 'text' },
	{ name: 'text', label: 'Text', type: 'textarea' },
	{ name: 'link', label: 'Link', type: 'link' },
];

const EMPTY_SERVICE = {
	icon: 0,
	iconUrl: '',
	title: '',
	text: '',
	link: '',
	linkText: '',
};

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, services } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Service Cards" textDomain="lc-tidyjs2026">
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
			<RepeaterField
				label={ __( 'Services', 'lc-tidyjs2026' ) }
				value={ services }
				onChange={ ( rows ) => setAttributes( { services: rows } ) }
				fields={ SERVICE_FIELDS }
				emptyRow={ EMPTY_SERVICE }
				layout="column"
			/>
		</EditorBlockShell>
	);
}
