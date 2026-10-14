import { __ } from '@wordpress/i18n';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { register, editorData } from '../../shared/editor';
import metadata from './block.json';

function Edit() {
	const inner = useInnerBlocksProps(
		{ className: 'nav' },
		{
			allowedBlocks: [ 'core/navigation', 'core/buttons' ],
			orientation: 'horizontal',
			template: [
				[ 'core/navigation', { overlayMenu: 'never' } ],
				[
					'core/buttons',
					{},
					[
						[
							'core/button',
							{
								text: __( 'Biljetter', 'studentspex' ),
								className: 'is-style-gold',
							},
						],
					],
				],
			],
		}
	);
	return (
		<header { ...useBlockProps( { className: 'site-header' } ) }>
			<div className="container site-header__inner">
				<span className="brand">
					<img
						className="brand__logo"
						src={ `${
							editorData().themeUrl
						}/assets/img/logo-white.png` }
						alt=""
					/>
				</span>
				<div { ...inner } />
			</div>
		</header>
	);
}

register( metadata, Edit, { inner: true } );
