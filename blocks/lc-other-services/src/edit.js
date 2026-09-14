import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const { heading } = attributes;
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Other Services" textDomain="lc-tidyjs2026">
			<TextControl
				label={ __( 'Heading', 'lc-tidyjs2026' ) }
				value={ heading }
				onChange={ ( value ) => setAttributes( { heading: value } ) }
				help={ __( 'Defaults to "Other Services" if left blank.', 'lc-tidyjs2026' ) }
			/>
		</EditorBlockShell>
	);
}
