import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { register, Scallop } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	return (
		<>
			<section { ...useBlockProps( { className: 'stage page-hero' } ) }>
				<div className="container">
					<p className="eyebrow">
						{ __( 'Gamla spex · år', 'studentspex' ) }
					</p>
					<h1>{ __( 'Uppsättningens titel', 'studentspex' ) }</h1>
					<p className="hero__sub">
						{ __(
							'Undertitel. Visas automatiskt från uppsättningen.',
							'studentspex'
						) }
					</p>
				</div>
			</section>
			<Scallop />
		</>
	);
}

register( metadata, Edit );
