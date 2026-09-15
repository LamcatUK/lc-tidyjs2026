import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import EditorBlockShell from '../../_shared/EditorBlockShell';

export default function Edit( { clientId } ) {
	const blockProps = useBlockProps( { className: 'container lc-tidyjs2026-editor-block' } );

	return (
		<EditorBlockShell blockProps={ blockProps } clientId={ clientId } title="LC Latest Guides" textDomain="lc-tidyjs2026">
			<p>{ __( 'No fields — shows the 3 latest posts automatically, in a slider.', 'lc-tidyjs2026' ) }</p>
		</EditorBlockShell>
	);
}
