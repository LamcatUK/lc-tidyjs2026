import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';
import RepeaterField from '../../_shared/RepeaterField';

const SERVICE_FIELDS = [
	{ name: 'icon', label: 'Icon', type: 'image', mimeTypes: [ 'image/svg+xml' ] },
	{ name: 'service', label: 'Service', type: 'link' },
	{ name: 'text', label: 'Description', type: 'text' },
];

const EMPTY_SERVICE = {
	icon: 0,
	iconUrl: '',
	service: '',
	serviceText: '',
	text: '',
};

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { title, intro, services, outro } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Service List" textDomain="lc-tidyjs2026">
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
				label={ __( 'Services', 'lc-tidyjs2026' ) }
				value={ services }
				onChange={ ( rows ) => setAttributes( { services: rows } ) }
				fields={ SERVICE_FIELDS }
				emptyRow={ EMPTY_SERVICE }
				layout="row"
			/>
			<TextControl
				label={ __( 'Outro', 'lc-tidyjs2026' ) }
				value={ outro }
				onChange={ ( value ) => setAttributes( { outro: value } ) }
			/>
		</EditorBlockShell>
	);
}
