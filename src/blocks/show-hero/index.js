import { __, sprintf } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { Notice } from '@wordpress/components';
import {
	Fields,
	Text,
	register,
	editorData,
	Icon,
	Scallop,
} from '../../shared/editor';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	const data = editorData();
	const show = data.currentShow;
	const p = { attributes, setAttributes };
	const style =
		show && show.cover_url
			? { '--hero-cover': `url(${ show.cover_url })` }
			: undefined;
	const blockProps = useBlockProps( {
		className: `stage hero${
			show && show.cover_url ? ' hero--cover' : ''
		}`,
		style,
	} );
	const year = show && show.year ? show.year : '';
	const lisse = ( show && show.lisse_url ) || data.defaultLisse;
	return (
		<>
			<Fields
				attributes={ attributes }
				setAttributes={ setAttributes }
				fields={ [
					{
						key: 'btn1Url',
						label: __( 'Länk, första knappen', 'studentspex' ),
						type: 'url',
						help: __(
							'Tomt = Nästa spex #biljetter',
							'studentspex'
						),
					},
					{
						key: 'showBtn2',
						label: __( 'Visa knappen "Sök spexet"', 'studentspex' ),
						type: 'toggle',
					},
					{
						key: 'btn2Url',
						label: __( 'Länk, andra knappen', 'studentspex' ),
						type: 'url',
						help: __( 'Tomt = sidan Sök spexet', 'studentspex' ),
					},
				] }
			>
				<p>
					{ __(
						'Omslagsbild, Lisse, år och premiärdatum hämtas från aktuell uppsättning.',
						'studentspex'
					) }{ ' ' }
					<a href={ data.settingsUrl }>
						{ __( 'Välj aktuell uppsättning', 'studentspex' ) }
					</a>
				</p>
			</Fields>
			<section { ...blockProps }>
				<div className="spotlight spotlight--left" aria-hidden="true" />
				<div
					className="spotlight spotlight--right"
					aria-hidden="true"
				/>
				<div className="stage__floor" aria-hidden="true" />
				<div className="container hero__grid">
					<div>
						{ ! show && (
							<Notice status="warning" isDismissible={ false }>
								{ __(
									'Ingen aktuell uppsättning vald – välj en under Spexet → Inställningar.',
									'studentspex'
								) }
							</Notice>
						) }
						<p className="hero__kicker">
							<strong>
								{ sprintf(
									/* translators: %s: year */ __(
										'Spex %s',
										'studentspex'
									),
									year
								) }
							</strong>{ ' ' }
							<Text
								{ ...p }
								attr="kickerText"
								tag="span"
								plain
								placeholder={
									show && show.premiere
										? sprintf( 'Premiär %s', show.premiere )
										: __( 'Premiär…', 'studentspex' )
								}
							/>
						</p>
						<Text
							{ ...p }
							attr="headline"
							tag="h1"
							formats={ [ 'core/italic' ] }
							placeholder={ __(
								'Rubrik – kursiv text blir guld',
								'studentspex'
							) }
						/>
						<Text
							{ ...p }
							attr="sub"
							className="hero__sub"
							plain
							placeholder={ __( 'Underrubrik', 'studentspex' ) }
						/>
						<ul className="hero__meta">
							<li>
								<Icon.calendar />
								<Text
									{ ...p }
									attr="dateText"
									tag="span"
									plain
									placeholder={
										show && show.premiere
											? `Premiär ${ show.premiere }`
											: __( 'Datum', 'studentspex' )
									}
								/>
							</li>
							<li>
								<Icon.pin />
								<Text
									{ ...p }
									attr="venueText"
									tag="span"
									plain
									placeholder={ __( 'Plats', 'studentspex' ) }
								/>
							</li>
						</ul>
						<div className="btn-row">
							<span className="btn">
								<Icon.ticket />
								<Text
									{ ...p }
									attr="btn1Text"
									tag="span"
									plain
								/>
							</span>
							{ attributes.showBtn2 && (
								<span className="btn btn--ghost">
									<Text
										{ ...p }
										attr="btn2Text"
										tag="span"
										plain
									/>
									<Icon.arrow />
								</span>
							) }
						</div>
					</div>
					<div className="hero__art is-tapped">
						<img className="hero__lisse" src={ lisse } alt="" />
						<p className="hero__art-tag">
							<Text
								{ ...p }
								attr="tagTitle"
								tag="span"
								className="hero__art-tag-title"
								plain
							/>
							<Text { ...p } attr="tagText" tag="span" plain />
						</p>
					</div>
				</div>
			</section>
			<Scallop />
		</>
	);
}

register( metadata, Edit );
