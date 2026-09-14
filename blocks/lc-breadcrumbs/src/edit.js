import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { attributes, setAttributes, clientId } ) {
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Breadcrumbs" textDomain="lc-tidyjs2026">
			<p>{ __( 'No fields — the trail is generated automatically from the page hierarchy.', 'lc-tidyjs2026' ) }</p>
		</EditorBlockShell>
	);
}
