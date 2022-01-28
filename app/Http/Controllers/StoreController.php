<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Cache;
use Session;

class StoreController extends Controller
{
    public function allowDisplayFAQs() {
        return ["http://www.soclean.com","http://www.inventorysource.com","https://www.overkillshop.com","https://www.cect-shop.com","https://www.lucchese.com","http://hollywoodmemorabilia.com","http://aosom.com","http://mpb.com","http://www.webstore.ansi.org","http://www.aplusrstore.com","http://www.bountyhunterwine.com","http://www.findmypast.com","http://www.findmypast.co.uk","https://www.pdpaola.com","http://www.pixelyoursite.com","http://legacyfoodstorage.com","http://www.nickis.com","https://minimuseum.com","http://www.store.e-cigarette-usa.com","http://www.wildemasche.com","https://www.siroko.com","http://www.steepandcheap.com","http://www.adultshop.com.au","http://www.petdrugsonline.co.uk","http://fabfitfun.com","http://wiredforwine.com","https://www.1800contacts.com","http://www.tilley.com","https://germaine-de-capuccini.co.uk","http://athleta.gap.com","http://www.joseph-fashion.com","https://www.inpixio.com","http://www.zachys.com","http://www.racetrackart.de","https://oldnavy.gap.com","http://www.allposters.com","https://www.zaful.com","http://www.lids.com","http://www.lids.ca","http://www.boohoo.com","http://www.lifeproof.com","http://www.bradfordexchangechecks.com","https://www.cookwithmilo.com","http://www.createandcraft.com","https://www.nvgallery.com","https://www.debragga.com","http://www.ebuyer.com","http://www.beautyexpert.com","http://www.edx.org","http://www.wondershare.com","http://www.aimersoft.com","https://ecig-city.com","http://www.shoesinternational.co.uk","http://www.pepejeans.com","http://www.undercovercondoms.com","http://www.pinkapple.com","http://waft.com","http://www.usamilitarymedals.com","http://stubhub.com","https://hidemy.name","http://pitboss-grills.com","http://www.childrenlearningreading.com","https://www.1000pipclimbersystem.com","http://pickupspanish.com","http://morningritualmastery.com","https://www.precisionmovement.coach","https://www.braintraining4dogs.com","http://www.jacksblowjoblessons.com","https://www.hockeytraining.com","http://magneticmessaging.com","http://zcodesystem.com","https://clevergizmos.com","http://www.vertex42.com","https://cbproads.com","https://www.mt4copier.com","http://www.zoowhiz.com","http://www.luckydays.tv","http://www.repairsurge.com","https:/www.musicshopeurope.com","http://www.skillshare.com","https://www.bstn.com","http://www.edureka.co","https://www.glossier.com","http://www.britishnewspaperarchive.co.uk","https://www.endsleigh.co.uk","http://mozaico.com","http://www.vinexus.de","https://www.rugsdirect.co.uk","http://www.shedstore.co.uk","http://euroflorist.se","https://www.budgettruck.com","https://www.etonshirts.com","http://www.diptyqueparis.com","http://www.totokaelo.com","http://www.babyshop.de","http://www.hotwire.com","http://www.michaels.com","http://www.futurelearn.com","https://lecol.cc","http://broxo.com","http://www.eroscillator.com","https://www.kohls.com","http://www.homedepot.com","https://www.adornmonde.com",
            "http://www.romwe.com","http://www.walmart.com","http://brouwland.com","http://www.zulily.com","https://www.buywake.com","http://www.jdsports.ie","http://talkspace.com","http://www.clearchemist.co.uk","http://www.camillaandmarc.com","http://www.ulta.com","http://www.lolarose.co.uk","http://www.lessonplanet.com","https://saledress.com","http://www.bitsbox.com","http://www.newegg.com","http://www.traegergrills.com","https://bootybands.com","https://www.gtracingchair.com","https://www.mindmovies.com","https://tipmrebuilders.com","https://www.clickstay.com","https://kyberlight.com","https://aromaretail.com","https://www.mooncup.co.uk","https://www.treehousesupplies.com","https://virginiaconcealed.com","http://www.hosteons.com","http://www.exoticindiaart.com","https://www.readingglassesetc.com","http://www.tiltedsole.com","https://www.glambot.com","https://batteryhookup.com","https://indiereader.com","https://www.recordingrevolution.com","http://www.jsdsupply.com","https://www.ericaziel.com","http://www.holdemmanager.com","https://www.enjukuracing.com","https://sweetcookies.org","https://vpsdime.com","https://www.printedkicks.com","https://store.doxymassager.com","https://bandwagonhost.com","https://soundstripe.com","https://www.omniaradiationbalancer.com","http://www.cyclepedia.com","https://motiondesign.school","https://www.oneclickroot.com","https://www.volumepills.com","https://www.pokertracker.com","http://dj-extensions.com","https://www.saaltco.com","https://pilotactive.com","https://highcarbhannah.co","https://arcticfoxhaircolor.com","https://www.nipyata.com","https://6ixice.com","http://simplyearth.com","http://puffingbird.com","https://monstrumtactical.com","https://getkush.io","https://www.hostatom.com","http://www.lifepixel.com","http://www.teafromtaiwan.com","https://www.hypnoticworld.com","https://travelingmailbox.com","https://www.sanebox.com","http://batchskiptracing.com","https://www.bettystoybox.com","https://paleopro.com","http://www.microbalancehealthproducts.com","http://www.wickedgoodcupcakes.com","https://scanz.com","http://balloon-world.co.uk","http://www.proko.com","https://promarinesupplies.com","https://sumup.com","https://www.migvapor.com","https://www.solomusicgear.com","http://mjarsenal.com","https://www.getpivo.com","https://timtam.tech","http://www.hyperspin.com","https://awakened-alchemy.com","https://tryshift.com","https://www.tuftandpaw.com","https://booksrun.com","https://www.creativefabrica.com","https://agogie.com","https://ambassadorwatches.com","https://www.drbrite.com","https://www.mandarinblueprint.com","https://andreasseedoils.com","https://www.concealedcarry.com","http://getyourprettyon.com","http://www.summerana.com","http://tactileturn.com","https://fit2b.us","https://thoughtcloud.net","https://www.skinnymixes.com","https://excelnode.com","https://www.libertyclassroom.com","http://teralifestyle.com","https://www.proofreadingacademy.com","https://www.gooddyeyoung.com","https://www.alphalion.com","http://www.karlkani.com","https://my.frantech.ca","https://www.racedayquads.com","http://www.mayarts.com","https://www.makewaterpure.co.uk","https://www.stockroom.com","https://www.insynchq.com","https://wordai.com","http://www.makemkv.com","https://alaindupetit.com","https://aprbiotech.com","https://rebuffreality.com","http://www.tentsile.com","https://www.highproxies.com","https://www.aelfriceden.com","https://xwpthemes.com","http://rinsekit.com","http://www.farmtovape.com","https://fastereft.com","http://shoptinyhouses.com","https://sexyrealsexdolls.com","https://www.dartscorner.co.uk","https://www.etizolab.com","https://www.vigrxplus.com","https://www.myverticaltracker.com","https://katsbotanicals.com","https://academy.mosalingua.com","http://www.voipo.com","https://topratedshoes.com","https://fandffirearms.com","https://lifeboostcoffee.com","https://pixelme.me","https://www.peepshowtoys.com","https://www.tunepocket.com","http://www.videoblocks.com","https://www.siteguarding.com","https://munchaddict.com","https://herbapproach.com","https://lolahemp.com","http://www.tribestlife.com","https://www.wtfast.com","https://urbanmachina.com","https://viabestbuys.com","https://binkybro.com","http://www.ihcginjections.com","http://devotionnutrition.com","https://ejuiceplug.com","https://wpbeaveraddons.com","https://e-sniper.com","https://poppers4u.com","https://www.waterscoaustralia.com.au","https://brainforza.com","https://shootingtargets7.com","https://www.postboxed.co.uk","https://www.misscire.com","https://rosewoman.com","http://www.dropship-spy.com","http://www.the-tremor.myshopify.com","https://frobutter.com","http://buyjumpropes.net","http://melodysusie.com","https://www.springeramerica.com","http://www.yogaswings.com","http://rocketprices.com","http://www.hawaiipharm.com","http://www.icolorcontactlenses.com","https://www.fodyfoods.com","https://www.sexysexdoll.com","http://www.bedandpillows.com","https://www.kirkpatrickleather.com","https://onglesdor.com","https://www.loveisproject.co","https://dutchseedsshop.com","https://pampadirect.com","https://angles90.com","https://www.urbalactiv.com","https://www.numiscorner.fr","https://www.tortoisetown.com","https://www.harum.io","https://pokatheme.com","https://www.bluebrainboost.com","https://www.concealmentexpress.com","https://www.combin.com/","https://www.modoker.com","https://unicornskincosmetics.com","https://invigoratedwater.com","https://japannettv.com","https://thinedges.com","https://www.theacoutlet.com","https://eycemolds.com","https://www.harmonitea.com","http://www.hostwinds.com","https://modernvaseandgift.com","https://www.treehuggerclothpads.com","https://www.scentsevent.com","https://habitnest.com","http://newipnow.com","http://www.socalmotogear.com","https://placeit.net","https://thehoneypot.co","https://soundupnow.com","https://www.optimoz.com.au","http://testkitplus.com","https://www.floorsome.com.au","https://reply.io","https://www.originalharvestkratom.com","http://www.theplrstore.com","http://b4i.travel","https://www.arabellahair.com","https://ewww.io","https://getcasely.com","https://www.halodakimakura.com","https://www.usedacdepot.com","https://oviwatch.com","http://southerncrossvelvet.com","https://rankerx.com","http://charliebanana.com","https://discovercbd.com","https://www.lawdepot.com","http://firewalla.com","https://buceplant.com","https://darkestfox.com","https://iconicstreams.com","https://cakestackers.com","https://sneakerserver.com","https://www.subtlbeauty.com","http://www.edenfantasys.com","http://www.malibustrings.com","https://blendmount.com","https://welcomed.com","https://www.sentimenttiming.com","http://www.meloncube.net","https://buddytag.myshopify.com","https://www.alandia.de","https://www.eliteweightloss.com","https://www.giftcrates.com","https://www.tokersupply.com","https://ikaprint.com/","https://medicalgearoutfitters.com","http://www.chandika.com","https://diye-liquidsupplies.com","https://simplespectrumsupplement.com","http://www.gotobus.com","https://99rdp.com","https://www.etchingexpressions.com","https://www.hemplucid.com","https://golden-boyz.com","http://yaeorganics.com","http://southwestarcheryusa.com","http://www.anymp4.com","http://www.lockedinlust.com","http://www.pawz.com","https://analytify.io","https://groceriesapparel.com","https://ojaienergetics.com","https://www.atplab.com","https://www.moneythumb.com","https://www.primallifeorganics.com","https://www.jekca.com","https://hazesmokeshop.ca","https://designbundles.net","https://therasage.com","https://www.utimi.com","https://americanlegendrider.com","https://cms2cms.com","http://www.glowcity.com","http://www.lupsona.com","http://www.sportsmogul.com","https://purevitaminclub.com","https://www.berkeywaterfilter.com","https://www.nestedbean.com","https://flippednormals.com","https://www.vapemerchant.nz","https://omgleds.com","https://www.supamegagreens.com","https://www.ftmessentials.com","https://tgrleafy.com","https://www.forgedfromfreedom.com","https://rachelyellin.com","https://www.rentcars.com","https://neurogum.com","https://www.baamboostudio.com","https://www.nolahmattress.com","http://www.pookapureandsimple.com/","http://www.dickatyourdoor.com","http://transformationslabs.com","https://seatylock.com","https://www.americanartdecor.com","http://www.anarchyoutdoors.com","http://www.gameservers.com","http://www.somnilight.com","https://sellfy.com","http://bombereyewear.com","https://atwoodrope.com","https://cannacomforts.com","https://www.mutstore.com","http://craftindustryalliance.org","http://wazoosurvivalgear.com","http://www.dreamstime.com","https://www.homezada.com","https://www.zon.tools","https://www.driverseducationofamerica.com","http://ejuicedb.com","http://www.secure.ipburger.com","https://mavictoria.com","https://studiosounds.com","https://www.vitalityextracts.com","https://www.megaknife.com","https://ketocandygirl.com","https://www.pandadoc.com","http://www.seaadventureexcursions.com","http://www.secure.cobionic.com","https://shoemgk.com","https://skulljewelry.com","https://www.3d.sk","https://www.greencultured.co","https://www.jadeleafmatcha.com","https://rsocks.net","https://makerflocrafts.com","https://www.trimleaf.com","https://cannabox.com","https://www.emaillistverify.com","https://www.goregenhealth.com","https://www.tipsandtricks-hq.com","https://www.thebeardlyman.com","http://replacealens.com","https://kozhanumbers.com","http://www.sleepycat.in","http://themely.com","https://omnishaver.com","https://swagtron.com","https://theblessedseed.com","https://www.sufio.com","https://www.wemakedancemusic.com","http://www.flipaquatics.com","https://www.yuminutrition.com","http://wisementrading.com","https://mysoftwarekeys.com","https://www.beautybyearth.com","https://goboardup.com","http://www.artkalfusebeads.com","https://timestales.com/","https://miraclemilkookies.com","https://loupedeck.com","http://www.viicode.com","https://apachepine.com","https://hifigo.com","https://regulargirl.com","https://republicanlegion.com","https://shockhosting.net","https://www.canadakratomstore.com","https://bluecrate.com","http://www.joahlove.com","https://logojoy.com","http://www.inkthemes.com","https://katsnaturals.com","https://store.mindnutrition.com","https://themeover.com","https://www.jcrowsllc.com","https://www.sparklehustlegrow.com","https://walkeepaws.com","http://brillianceincommerce.com","http://www.csshero.org","http://www.fishersfinery.com","http://www.showthemes.com","https://ipostal1.com","https://themehunk.com","https://www.sellbrite.com","http://www.plantessentials.com.au","https://montemlife.com","https://nemopro.com","https://www.hempiredirect.com","https://www.libertariancountry.com","http://bomibox.com","https://en.getmoona.com","http://taoclean.com","https://golfsimulatorstore.com","https://reallygreatfood.com","https://www.apolloecigs.com","https://www.belugacdn.com","https://www.lashliner.com","https://www.usatuff.com","https://eu.aimcontrollers.com","https://humboldtvapetech.com","https://www.grittyspanish.com","https://www.netzerocompany.com","http://levergear.com","https://manycam.com","https://templ.io","https://www.amzanalyzer.com","https://www.shortpar4.com","https://www.urbanhydration.com","https://www.onevps.com","http://www.magic-flight.com","https://www.streakersports.com","https://kinderbeauty.com","http://boucleme.co.uk","http://earthrunners.com","http://sunflowerjewels.com","http://www.dragondoor.com","http://www.orderhealth.in","https://irockersup.com.au","https://mealplanmap.com","https://wpquads.com","https://www.apricotpower.com","https://www.bigscoots.com","https://www.craftsman-book.com","https://smartpodcastplayer.com/","https://flomattress.com","https://5percentnutrition.com","https://matcha.com","http://coconutbowls.com","https://vermafarms.com","http://cleanblend.com","https://www.cluschoolofthespirit.com","https://www.originalmagicart.store","https://www.shoefreaks.ca","http://fitmaxipool.com","https://livebearded.com","https://www.lacelab.com","https://www.equipfoods.com","http://infinitewp.com","http://scentfill.com","http://www.brainev.com","http://www.foodphotographyguides.com","https://harapad.com","https://www.rpgstash.com","http://www.buyproxies.org","http://www.gogreenhemp.com","https://www.shuttlefare.com","https://blissknifeworks.com","https://dbliquids.com","https://flixpress.com","https://www.ledfactorymart.com","https://www.reliablerxpharmacy.com","https://www.seventeenth-watches.com/","https://www.time4vps.eu","http://www.tungstenfashions.com","https://thedealminute.com","https://www.flashbackltd.com","http://actproxy.com","http://creamhaus.us","http://members.ilslearningcorner.com","http://paragonfn.com","http://www.blackhattoolz.com","http://www.dropified.com","http://www.pocketmags.com","https://agethemes.com","https://akoco.com","https://corewalking.com","https://puppiesmakemehappy.com","https://shop.perfectketo.com","https://www.cloudbet.com","https://www.earthsnaturalclay.com","https://www.lovelifesupplements.co.uk","https://www.samedaysupplements.com","https://www.smile4you.co.uk","http://www.rdjtrucks.com","http://www.rfvitamins.com","https://www.freshroastedcoffee.com","https://fensens.com","https://bodygym.com","http://www.abrosoft.com","http://www.dogloverstore.com","https://javapipe.com","https://leadsbridge.com","https://really-simple-ssl.com","https://supremewizard.co.uk","https://torguard.net","https://www.goblingaming.co.uk","https://www.luxurysoundsociety.com/","https://www.proxy-n-vpn.com","https://www.melopero.com","https://populum.com","https://kapowmeggings.com","http://7rueparadis.com","http://bazzininuts.com","http://murchison-hume.com","https://shophappymango.com","http://www.hexlox.com","https://ruggedanddapper.com","https://wellearthgoods.com","http://vanguardsmoke.com","http://www.angelicvibes.com","http://www.digitsu.com","http://www.mapsmarker.com","http://www.neemking.org","https://makefreedom.com","https://www.freedomjapanesemarket.com","https://www.nerdkungfu.com","https://renpho.com","https://knittersplanner.com","http://www.trythecbd.com","https://atharapure.com","http://hippie-pants.com","http://www.edutige.net","http://www.intotheam.com","https://fiberreed.de","https://iceline-hosting.com","https://klevercase.com","https://store.drjockers.com","https://www.modernproducers.com","https://www.releasetechnique.com","https://www.tkflt.com","https://www.vivamacity.com","https://wegotnuts.com","https://rodmachado.com","https://www.petwinery.com","https://www.petespaleo.com","http://abdlfactory.com","http://appfinite.com","http://tacticalsoap.com","http://www.rocketstreams.tv","http://www.skincarejungle.com","http://www.starproxies.com","https://bibado.co.uk/","https://howmanyextension.com","https://lureessentials.com","https://medsitis.com","https://moleculestore.com","https://www.burtonnutrition.com","https://www.glutenfreemall.com","https://www.plrassassin.com","https://www.salesource.io","https://www.buynba2kmt.com","https://mayastickers.com","https://www.pioneerminisplit.com","https://www.valtcan.com","https://goldpresidents.com","https://ambrosianutraceuticals.com","https://www.fatwoodbeard.com","https://www.clubfitwear.com/","https://www.electricspokes.com","https://www.rootedtreasure.com","https://padmate-tech.com","https://astoria-activewear.com","http://www.dailyfaithplr.com","http://www.epubor.com","http://www.lighterusa.com","http://www.soundofsleep.com","https://cbdgum.networkofhemp.com","https://cravingkratom.com","https://crowdsigns.com","https://digitanza.com","https://keyshorts.com","https://kitchenbloggers.com","https://luckstock.com","https://oxygenpools.com","https://rareplayingcards.com","https://shoplazydog.com","https://www.buyarrive.com","https://www.kapwing.com","https://www.leather-moccasins.com","https://www.profitaccumulator.co.uk","https://greencloudvps.com","https://www.dedispec.com","http://www.golfstats.com","https://www.myimprov.com","https://thejewelerz.com","http://www.shop.makerfire.com","https://hoodielab.com","https://dabix.com","https://woomoreplay.com","https://www.femallay.com","https://www.smallsforsmalls.com","http://evolvingnation.com","http://store.uspizzateam.com","http://www.amber-hk.com","http://www.ebrewsupply.com","http://www.thephilosophie.com","https://clim8.com","https://diabetickitchen.myshopify.com","https://eciggity.com","https://mybookie.ag","https://shop.shedwindowsandmore.com","https://www.ksresin.com","https://www.luxeautoconcepts.net","https://www.pdfautomationstation.com","https://www.theveritasfarms.com","https://eptmusa.com","https://www.invent.org","https://www.welooc.com","https://mattelashes.com","https://www.omniprogear.com","https://navanskincare.com","https://www.bitmapbooks.co.uk","http://detroitgrooming.com","http://legion-precision.com","https://www.thecontentplanner.com","http://www.aliexpress.com","http://www.gigabyke.com","http://www.icnbuys.com","http://www.realbodywork.com","https://honeycity.com.sg","https://whc.ca","https://www.bluestein.de","https://www.finsfishing.com","https://www.giftcardspread.com","https://www.rackpooltables.com","https://www.rotometals.com","https://www.vitalifehealth.com","https://www.wowafrican.com","https://wpcomplete.co","https://www.sarmlabz.com","https://ekster.com","https://clearlybasics.com","http://4rabet.com","https://blueberrylaneshop.com","https://esteez.com","https://www.strengthshopusa.com","http://designmodo.com","http://droidbox.co.uk","https://brilliantk9.com","https://unity3d.com","https://www.bluesummitsupplies.com","https://www.csamedicalsupply.com","https://www.flipflopspanish.com","https://www.ihostvps.net","https://www.itthinx.com","https://www.jewelrypersonalizer.com","https://www.mychocolatebox.nz","https://www.rushinupholsterysupply.com","https://www.smartydns.com","https://xmiles.co.uk","https://raiseyouredge.com","https://gobloomandglow.com","https://afflift.com","https://stayclassy.in","https://viablekratom.com","https://lookslikesummer.com","https://www.aberlite.com","http://hand2noteprotools.com","http://instructormusic.com","http://ronniecoleman.net","http://tweetadder.com","http://www.boostupsocial.com","http://www.kentooz.com","http://www.mycentsofstyle.com","https://bomsocks.com","https://meranom.com","https://nutriprofits.com","https://purevitaminclub.com","https://shop.getkeyto.com","https://smgains.com","https://waxdirect.com","https://www.aromis.co","https://www.atmosphereaerosol.com","https://www.connectedclubs.co.uk","https://www.cuvanaecigar.com","https://www.dadabc.com","https://www.eastwoodriding.com","https://www.flyby.co","https://www.gasbike.net","https://www.kosherwine.com","https://www.makeupmaniacs.com","https://www.pexgle.com","https://www.ropelacesupply.com","https://www.sploofybrand.com","https://www.visahq.com","https://www.tealife.com.au","https://odin-gear.com","https://hudway.co","https://caliconnected.com","https://matguardusa.com","https://www.x28fitness.com","https://www.hempednyc.com","http://lifetoken.com","http://rdparena.com","http://www.rogueamericanapparel.com","https://laptopkey.com","https://paracable.com","https://penonaudio.com","https://prjewel.com","https://www.belmondobeauty.com","https://www.clearestimates.com","https://www.designilcode.com","https://www.donebettergifts.com","https://www.globehost.com","https://www.nuciya.com","https://www.zerofuckscoin.com","https://premierbodyarmor.com","https://wclovers.com","https://thecoldestwater.com","https://saasant.com","https://www.barebonesbroth.com","https://menolabs.com","http://shop3duniverse.com","https://www.sonnycosmetics.com","https://www.inspireuplift.com","https://www.alienmood.com","https://pcktvapor.com","https://glassyeyewear.com","https://www.necksaviour.com","https://www.brooklynbiltong.com","https://thebalimarket.us","https://thelastcoat.com","http://ajcrimson.com","https://kiierr.com","http://www.essentialbracelet.com","https://skyvia.com","http://henty.cc","http://shop.divingexpress.com","http://www.designcrowd.com","http://www.flitz.com","http://www.goodsync.com","http://www.MixtEnergy.net","http://www.musicbooksplus.com","http://www.sellbackyourbook.com","http://www.seothemes.com","http://www.thesongbirdcollection.com","http://www.vmagicnow.com","https://adrenalfatiguesolution.com","https://anatomie.com","https://oceanwp.org","https://progotv.com","https://snthostings.com","https://tampontribe.com","https://uk.bestself.co","https://wpglobus.com","https://www.aikoncms.com","https://www.dadhatsupplyco.com","https://www.hominter.com","https://www.ovdoll.com","https://www.rssground.com","https://www.safesleevecases.com","https://www.straxx.com","http://sportybella.com","https://www.jet-blaster.com","https://www.familytimefitness.com","https://www.trenchdrainsupply.com","https://soapkorner.com","https://www.helloaxis.com","https://kristalizejewelry.com","https://www.nutri-patch.com/","https://chassisformen.com","https://www.pushcases.com","http://unwash.com","https://liquidgoldhairproducts.com","http://secretoftheislands.com","http://theminecrafthosting.com","http://www.clovux.net","http://www.gamenerdz.com","http://www.grubhub.com","http://www.htfw.com","http://www.paradisefibers.com","http://www.thewashitapeshop.com","http://www.withernode.com","https://alienstreams.net","https://alshobbies.co.uk","https://behavioruniversity.com","https://bentology.com","https://cuntgifts.com","https://gamehosting.co","https://greenz.ca","https://iristech.co","https://remixd.co.uk","https://tjclark.com","https://warfareplugins.com","https://www.activegamehost.com","https://www.anthonysgoods.com","https://www.blueheronarts.com","https://www.budgetvapors.com","https://www.comingbuy.com","https://www.daycounts.com","https://www.goodstate.com","https://www.instamosaicstudio.in/","https://www.rescuetime.com","https://www.zindee.com","https://www.thewholesomemonkey.com","https://nannyparentconnection.com","https://straightrequestproducts.com","https://gmchosting.com","https://www.blackdiamondpigments.com","https://powerhouseaffiliate.com","https://www.template.net","https://rapidcpapmask.com","https://dexprotection.com","http://www.maluma-green.com","https://planetdesert.com","https://heeltread.com","https://haakaausa.com","https://annebeauty.ltd.uk","https://www.store.dogwalkinsync.com","https://www.simplycarbonfiber.com","https://candelles.com","https://denpasoft.com","https://glassdippen.com","http://rebecca-page.com","http://shop.rveducation101.com","http://uniquecorals.com","http://www.goosevpn.com","http://www.maxcatchfishing.com","http://www.rugstown.com","http://www.screenpresso.com","http://www.skinbaron.de","https://boutiquemags.com","https://dealjumbo.com","https://goalscape.com","https://lillylashes.com","https://project-gc.com","https://therogueenergy.com","https://vahosting.net","https://www.buypersonalproxy.com","https://www.euroclinix.net","https://www.fontspring.com","https://www.geekstorage.com","https://www.onedollarlashclub.com","https://www.salviaextract.com","https://www.sewalittleseam.com","https://www.shatterbatter.com","https://www.superloot.co.uk","https://www.teamstores.com","https://www.lolga.com","https://thxsilk.com","https://readingraphics.com","https://coffeeovercardio.com","https://whimsystamps.com","http://www.prettygifted.co.uk","https://chakraopenings.com","https://riddleoil.com","https://www.loomsolar.com","https://healthyhumanlife.com","https://madebyradius.com","https://ovusmedical.com","http://barefoot-science.com","http://brightearthfoods.com","http://store.hardlotion.com","http://www.billing.serenityservers.net","http://www.gpzweb.com","http://www.qualitycage.com","http://www.roxservers.com","https://allthingsrealestatestore.com","https://florabowley.com","https://lyhmehosting.com","https://openside.com","https://petitestitchery.com","https://podavach.store","https://stockinvest.us","https://swoleaflabs.com","https://taostar.com","https://vpn.ac","https://wowelifestyle.com","https://www.bandofclimbers.com","https://www.i3dthemes.com","https://www.lucklessclothing.com","https://www.madazmoney.com","https://www.magicshop.co.uk","https://www.skullsplitterdice.com","https://www.tmzvps.com","https://www.truehost.com.ng","http://www.ghosthack.de","https://www.omarimc.com","https://serverblend.com","https://bestdealplr.com","https://www.vagabond-life.com","https://www.allbarnwood.com","https://www.downlitebedding.com","https://strengthgenesis.com","https://ledroomlights.com","https://craftybase.com","https://www.kappi.com.au",
            "https://biglifejournal.com","https://bumpboxx.com","https://drawlucy.com","https://drjoedispenza.com","http://www.cyberpowersystem.co.uk","http://www.mheducation.com","http://www.flixbus.com","https://glazedinc.com","http://www.gopowersports.com","http://grillagrills.com","https://www.kachava.com","https://islaidabracelets.com","https://jerkygent.com","https://jewelcove.co","https://meimeiobjects.com/","https://myallstarnutrition.com","https://nailboo.com","http://www.whitemountainpuzzles.com","https://simple-shot.com","http://www.store.bendyandtheinkmachine.com","https://theseraphinacollection.com","https://tojagrid.com","https://toolszap.com","https://vaped.ca","https://voketab.com","https://www.academicexcellence.com","https://www.aoeah.com","http://www.aroma360.com","http://carvedesigns.com","http://duolingo.com","https://www.ezyroller.com","https://www.iptvsubscription.tv","http://knotty.com.au","http://www.pumphaircare.com","https://www.ltkeepsakes.com","http://www.milspin.com","http://www.naturesideal.com","https://outergoods.com","http://www.partshub.ca","http://www.peppigel.com","http://www.pokersnowie.com","https://www.productionmusiclive.com","https://samplize.com","https://www.smytten.com","https://www.smytten.com","https://www.vesselbrand.com","http://www.vuugo.com","https://www.writefromtheheartkeepsakes.co.uk","https://123nourishme.com.au","https://2awarehouse.com","https://arenastrength.com","https://pebblehost.com","https://boxofcolor.com","http://www.deciem.com","https://dixiebellepaint.com","http://www.dribbleup.com","https://faircannacare.com","https://freshlions.com","https://gethypebox.com","https://ggservers.com","http://pinkcherry.com","http://uhaul.com","http://www.landsend.com","http://www.manieredevoir.com","http://www.on-running.com","http://www.roblox.com","http://www.runnerinn.com","http://www.salomon.com","http://www.stradivarius.com","http://www.tatacliq.com","http://www.warthunder.com","https://i49.net","https://livelyliving.com.au","https://looka.com","https://madetomeasureglassuk.com","https://uzmarketing.com","http://weboost.com","https://microbeformulas.com","https://mylifeguardshop.com","http://www.naturamarket.ca","http://www.olymptrade.com","https://www.ownboard.net","https://www.pnxbet.com","https://ramshard.com","http://www.sunshinesisters.etsy.com","https://smilelove.com","https://spectra-baby.com.au","http://www.stealthformen.com","https://taptes.com","https://thegoldenmonk.com","http://doordash.com","http://plumpaper.com","https://viddyoze.com/","https://vlebazaar.in","http://1clickprint.com","https://www.3chi.com","https://www.5dtactical.com/","https://www.7daysperformance.co.uk","http://www.midatlantic.aaa.com","http://plexaderm.com","https://www.ar15part.com","https://www.bellavitaorganic.com","https://www.ceratac.com/","http://cheapweed.ca","http://www.cozzoo.com","https://www.crabdynasty.com","https://www.doradofashion.com","https://www.dux-soup.com","http://early2bedshop.com","http://www.flowerwindowboxes.com","https://www.hosterpk.com","https://www.infuzehydration.com","https://www.lintonseafood.com","http://manyvids.com","https://www.noraross.com/","https://www.odinworks.com","https://www.organicolivia.com","https://www.savingology.com","https://www.sbd-usa.com","https://selfdecode.com","http://shopmoment.com","https://www.techlandbd.com","https://www.texaspowerbars.com","http://www.thebookshoponline.com","https://www.thegoboat.com","http://tifosioptics.com","https://www.uklash.com","http://wetokole.com","https://www.windowparts.com/","https://www.yumasia.co.uk","https://zamzshop.com","http://www.americantrucks.com","http://gunbroker.com","https://mycraftsource.com","http://shoeshow.com","http://uworld.com","http://pointsprizes.com","http://vaulteksafe.com","http://ets.org","http://panago.com","http://icaregifts.com","https://www.dndbeyond.com","http://alltrails.com","http://deporvillage.com","https://www.ikonpass.com","http://ixl.com","http://escapefromtarkov.com","https://www.homeagain.com","https://cebroker.com","http://pointsprizes.com","http://www.flynnohara.com","http://salonsdirect.com","http://farmgirlflowers.com","https://www.renderforest.com","http://teladoc.com","https://pizzaluce.com","http://cambly.com","http://gunprime.com","https://www.faceit.com","http://shevibe.com","http://www.grabagun.com","http://manychat.com","http://www.puffco.com","http://www.fbmarketplace.org","https://www.cubelelo.com","http://www.netmarble.net/","https://www.yoshinoyaamerica.com","https://rmadefense.com","https://support.rapidgator.net/","http://brainscape.com","http://www.amazon.in","https://www.rvtripwizard.com","http://www.toasttab.com","http://domestika.org","http://www.rvpartscountry.com","http://www.smokers-outlet.com","http://www.okcupid.com","https://www.sepaq.com","https://maxbrakes.com","http://www.causebox.com","http://boatsetter.com","http://gotinder.com","http://budsgunshop.com","http://54thstreetgrill.com","https://www.lynk.co.in","https://thaiexpress.ca","https://urbanplates.com","http://www.strengthshop.co.uk","http://www.mahoganybooks.com","http://www.showbags.com.au","http://www.cvlinens.com","https://www.partsips.com","http://www.blisslights.com","http://www.kpoptown.com","http://culturefly.com","https://www.getoemparts.com","http://www.shop.carhartt-wip.com","http://us.bape.com","https://www.britbox.com","http://travel2be.com","https://www.mypetpeed.com","http://westcoastvapesupply.com","http://www.strava.com","http://www.ccavenue.com","http://www.shop101.com","http://builds.io","http://www.zoosk.com","http://www.rareseeds.com","http://www.citylips.com","http://azardisplays.com","http://www.flyerspizza.com","http://www.docmj.com","http://www.bamwholesaleparts.com","http://www.makestickers.com","http://planetfitness.com","http://discountbandit.com","https://bolay.com","http://onohawaiianbbq.com","https://www.cosmoprof.com","http://playstation.com","http://brandymelvilleusa.com","http://pennyskateboards.com","http://roseroseshop.com","http://travelgenio.com","https://zwift.com","https://www.crossborderxpress.com","http://cyclebar.com","http://www.crestwhitestrips.co.uk","http://arianagrande.com","http://www.dividend.com","http://intexcorp.com","https://www.esthemax.com","http://www.gardenwinds.com","http://www.denmanbrush.com","http://flirt4free.com","http://gunbuyer.com","http://bearcreekarsenal.com","http://doverstreetmarket.com","http://www.storesupply.com","https://www.bubbakoos.com","http://www.ktown4u.com","http://persil.com","http://www.ereplacementparts.com","http://www.garden-gear.co.uk","http://www.angellift.com","https://www.cmlf.com","http://gatorade.com","http://www.silvercross.co.uk","http://citizenshipper.com","http://www.ollies.us","http://baldor.com","http://www.billieeilish.com","http://pacificcatch.com","http://lovense.com","http://irishsetterboots.com","http://typeform.com","https://www.proctoru.com","https://thalappakatti.com","http://ecstuning.com","http://ruralking.com","http://www.growace.com","http://www.marrow.org","https://www.ricepo.com","http://featherliteshoes.com","http://olaplex.com","http://zebit.com","http://www.maxicoffee.com","http://ripndipclothing.com","http://vergegirl.com","http://rammount.com","http://canadiantire.ca","https://www.fresnochaffeezoo.org","http://www.spareroom.co.uk","http://mrlube.com","http://bike24.com","https://dashskin.com","https://www.eagleview.com","http://hipcamp.com","http://www.4rsgold.com","http://fixdapp.com","http://www.mindmeister.com","http://www.shop.dtdc.com","http://www.dogcityandco.com","http://www.wholesalefashionshoes.com","https://www.choptsalad.com","http://www.cloudacademy.com","https://www.idleminertycoon.com","http://www.mycraftstore.com","http://www.napalmrecords.com","http://vapesocietysupply.com","https://factorypure.com","http://internetdownloadmanager.com","http://www.makeplayingcards.com","http://www.thelittlegreenbag.co.uk","http://us.xyzprinting.com","http://waiter.com","https://datbootcamp.com","http://sharkscope.com","http://www.goodstartpackaging.com","http://swimmingpoolliners.com","http://memphisshades.com","http://www.sunnxt.com","https://www.coveranything.com","https://neworleanspizza.com","http://gopuff.com","http://key.me","http://payrange.com","http://www.healthifyme.com","https://hylinecruises.com","http://spypoint.com","https://www.elevateapp.com","http://epicgames.com","http://tinyrufflesboutique.com","http://stayathome.com","http://pitapit.com","http://www.oysho.com","https://www.tuxmat.ca","http://castelli-cycling.com","http://gameloot.in","http://www.masterclip.co.uk","https://barlowstackle.com","http://cellunlocker.net","http://www.cigars-of-cuba.com","https://www.delybazar.com","http://schweser.com","http://www.lululemon.co.uk","http://maisonmartinmargiela.com","http://www.pikeplacefish.com","http://presonus.com","http://xsplit.com","http://www.ar500-targets.com","http://www.bugshirt.com","http://bulkbarn.ca","https://benshot.com","https://www.fs1inc.com","https://www.landmarkhw.com","http://www.1aauto.com","http://www.bigbadtoystore.com","https://www.agardenpatch.com","http://www.boomf.com","http://e-infin.com","http://www.mec.ca","http://tankionline.com","http://dutailier.com","http://freshhealthnutrition.com","http://www.poolsuppliescanada.ca","http://www.plantshed.com","https://www.sitkagear.com","http://tradingview.com","http://fareharbor.com","https://northwoodsoutlet.com","http://zarahome.com","http://www.canadaqbank.com","http://www.careersafeonline.com","http://gogovan.sg","https://allcornhole.com","http://arkencounter.com","http://crush-crush.wikia.com","http://www.holts.com","http://us-fanatec.com","http://tribute.co","http://quizlet.com","http://jacksonvillezoo.org","http://fairharborclothing.com","http://flagstaffextreme.com","http://performanceplustire.com","https://www.highseer.com","http://abbys.com","http://www.bordines.com","http://www.foxairsoft.com","http://pof.com","http://spearmintlove.com","http://www.bicyclebuys.com","http://www.scrubscanada.ca","http://alphaleteathletics.com","http://www.nataliediamonds.com","http://rvtrader.com","http://www.ticketsatwork.com","http://huel.com","https://www.restore-a-deck.com","https://www.benny-co.com","http://www.thomann.de","https://www.dashmedical.com","http://clutter.com","http://www.lovestylize.com","http://www.easyparcel.my","http://pruvitnow.com","https://activatedyou.com","http://seikousa.com","http://www.slip.com.au","http://waybackburgers.com","https://coach-net.com","https://athleanx.com","http://www.ayumakeup.ie","http://www.tacocabana.com","https://www.sleepcountry.ca","http://www.farmskins.com","http://golfwang.com","http://bushnell.com","https://www.mrmulch.com","http://naturalcycles.com","http://www.asdatyres.co.uk","http://www.dunnedwards.com","http://makartt","https://agendio.com","https://www.eradimaging.com","http://brother-usa.com","http://www.ufile.ca","https://secure.balanceit.com","https://www.buyritebeauty.com","http://wp-rocket.me","http://tootimid.com","http://fruugo.us","http://gshock.com","http://www.4over.com","http://www.cassanos.com","http://www.musthave.co.uk","http://santacruzskateboards.com","http://fun-spot.com","http://www.colouredcontacts.com","http://mercari.com","http://bunnings.com.au","http://sunplay.com","http://lloydmats.com","http://motionarray.com","https://marshalldrygoods.com","https://windshieldhub.com","http://www.dyersonline.com","http://cocooncenter.co.uk","https://www.oldtowntequila.com","http://flukerfarms.com","http://www.marsblade.com","https://rcimetalworks.com","http://headspace.com","http://www.plantogram.com","http://www.busuu.com","http://funimation.com","http://magpul.com","http://goldengoosedeluxebrand.com","http://www.gracobaby.com","http://www.provenwinners.com","http://www.foxitsoftware.com","https://www.bibibop.com","http://brazzers.com","http://www.andertons.co.uk","http://www.esslinger.com","https://www.leatherseats.com","http://balibodyco.com","http://hyperxgaming.com","http://www.skirack.com","http://blantonsbourbonshop.com","https://www.bumperbully.com","https://www.everydayautoparts.com","https://www.identogo.com","http://shatila.com","http://www.inyopools.com","http://www.juno.co.uk","https://stonerspizzajoint.com","https://vonlane.com","http://www.eburgess.com","http://cafezupas.com","http://www.canadabeautysupply.ca","http://www.clement.ca","https://www.goldfishswimschool.com","https://one-delivery.co.uk","http://www.snowleader.com","http://zabitat.com","http://thebagster.com","http://manhattanprep.com","http://yoast.com","http://www.dabrim.com","http://www.benchmade.com","http://www.ibpsguide.com","http://www.innerengineering.com","http://www.remarreview.com","http://www.squeezedonline.com","http://www.store.fakku.net","http://zoom.us","http://jimmyjohns.com","http://kpopmart.com","http://pitvipersunglasses.com","http://www.woodpeck.com","https://www.pointzeroenergy.com","https://www.pointzeroenergy.com","https://bankrbrand.com","https://www.empowerservers.com","https://orpheudecor.com","https://redrestore.com","https://orgonegenerator.com","https://wpjobster.com","https://www.annieandoak.com","https://www.torushydro.com","https://snownode.com","https://maryjaneexpress.org","https://www.fyicbd.com","https://xenspec.com","https://www.ilmotor.com","https://cosmiccuts.com","https://strainsupermarket.com","https://www.mshair.co.uk","https://www.rovedashcam.com","https://comstar.tv","https://www.cavpshost.com","https://fxvps.biz","https://nervereverse.com","https://kasentex.com","http://www.breastmilkjewelry.com","https://yourcatbackpack.com","https://www.akuspike.com","http://www.phunzone.com","https://onohosting.com","https://webeyesoft.com","https://ethoscarcare.com","https://servernetic.com","https://papersunday.com","https://www.vps9.net","https://maddiet.co","https://www.junglereels.com","http://www.asmarequestrian.com","https://cleverbotanics.com","https://www.kegelbell.com","https://lyfebooks.co","https://steamandgo.com","https://www.hostseba.com","https://www.drinco.com/","https://royal-box.eu","https://graphicloot.com","https://4korfitness.com","https://www.vikingsbrand.co","http://www.solestop.com","https://buylowgreen.com","https://livingthegoodlifenaturally.com","https://www.themegpl.com","https://www.jetorbit.com","https://lookingvibrant.com","https://nutriflair.com","https://www.zaaptech.com","https://www.themancompany.com","http://lujobox.com","https://www.squarepaste.com","https://www.aureliaatelier.com","https://www.hostsolutions.ro","http://www.miamelon.com","https://madeinmichigan.com","https://mybookcave.com","https://hostlelo.in","http://www.hostasset.net"
        ];
    }

    public function getLatestStore() {
        $stores = DB::table('stores')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        echo json_encode(['data' => $stores]);
    }

    public function nameWithKeyword($name) {
        if (stripos($name, ' coupon') || stripos($name, ' coupons')) {
            return $name .= ' & Promo codes';
        } elseif (stripos($name, ' promo')) {
            return $name .= ' & Discount codes';
        } elseif (stripos($name, ' voucher')) {
            return $name .= ' & Coupon codes';
        } elseif (stripos($name, ' discount')) {
            return $name .= ' & Coupon codes';
        } else {
            return $name .= ' Coupons & Promo codes';
        }
    }

    public function checkStringInLastPosition($str, $findMe) {
        $l = strlen($str);
        if (strrpos($findMe, '-coupons') === 0)
            return false;
        $t = strrpos($findMe, '-coupons') + strlen($findMe); 
        if ($l === $t) {
            return true;
        }
        return false;
    }

    public function randomLatestStores($quantity) {
        $arrRandomKeywords = [
            '[STORENAME] Coupons',
            '[STORENAME] Discount',
            '[STORENAME] Discount Code',
            '[STORENAME] Promo Code',
            '[STORENAME] Coupon',
            '[STORENAME] Deal',
            '[STORENAME] Coupon [YEAR]',
            'Get [STORENAME] Discount Code',
            'Coupon [STORENAME]',
            'Get Coupons for [STORENAME]',
            '[DOMAIN] Coupon',
            'Coupon [DOMAIN]',
            'Discount [DOMAIN]',
            '[DOMAIN] Discount Code',
        ];
        $arr = [];
        $result = DB::select("select id,name,alias,store_url from stores where store_url not like '%etsy.com%' AND random() < 0.01 limit $quantity");
        $year = Carbon::now()->format('Y');
        foreach ($result as $item) {
            $domain = str_replace('https://', '', $item->store_url);
            $domain = str_replace('http://', '', $domain);
            $domain = str_replace('www.', '', $domain);
            $domain = str_replace('/', '', $domain);
            $ranKey = rand(0, 13);
            $t = [];
            $struct = $arrRandomKeywords[$ranKey];
            $newStoreName = str_replace('[STORENAME]', $item->name, $struct);
            $newStoreName = str_replace('[DOMAIN]', $domain, $newStoreName);
            $newStoreName = str_replace('[YEAR]', $year, $newStoreName);
            $t['name'] = $newStoreName;
            $t['alias'] = $item->alias;
            array_push($arr, $t);
        }
        return $arr;
    }

    public function indexForUpdateCoupon($alias) {
        $dataSeo = [
            'seo' => [
                'title' => 'Discount Voucher Title index controller overwrite',
                'keywords' => 'Discount Voucher keywords index controller overwrite',
                'description' => 'Discount Voucher description index controller overwrite',
                'image' => ''
            ]
        ];

        $dataPage = ['dcCount' => '9,419'];

        $sDetail = DB::table('stores')->where('alias', '=', $alias)->where('countrycode', '=', 'US')
            ->where('status', '=', 'published')->first();


        $sDetail = (array)$sDetail;

        $allData = array_merge($dataPage, $dataSeo);
        $allData['store'] = $sDetail;
        $allData['store']['coupons'] = [];
        $allData['store']['relateStores'] = [];
        $allData['store']['expiredCoupons'] = [];
        $allData['store']['similarStores'] = [];
        $allData['arrVerified'] = [];
        $allData['arrStickyCp'] = [];
        $allData['arrCouponsNormal'] = [];
        $allData['arrCouponsRemote'] = [];
        return $allData;
    }

    public function index(Request $request, $alias = '') {
        $data['refer'] = !empty($request->all()['ref']) ? $request->all()['ref'] : '';
        $storeAlias = strtolower($alias);
        
        $store = DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order', 'social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();


        $store = (array)$store;
        $coupons = [];
        $childStores = [];

        $customFAQ = [];$customSavingTip = [];
        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['originalStoreName'] = $store['name'];
            $store['name'] = $storeName;

            /* Check if in Filter Mode */
            $sessionKey = 'coupon_type_' . $storeAlias;
            $arrFilterByCouponTypes = !empty(Session::get($sessionKey)) ? Session::get($sessionKey) : []; // this variable is an array of selected Coupon Type

            if(empty($arrFilterByCouponTypes)){
                // $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                    // return DB::select(DB::raw(
                $coupons = DB::select(DB::raw(
                        "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    AND c.coupon_type NOT IN ('FAQ','Saving Tips')
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                    ));
                // });
            }else{
                if(!empty($arrFilterByCouponTypes[0]) AND $arrFilterByCouponTypes[0] == 'All'){
                    $queryFilterByCouponTypes = '';
                }else{
                    $strFilterByCouponTypes = "('" . join("','", $arrFilterByCouponTypes) . "')";
                    $queryFilterByCouponTypes = "AND c.coupon_type IN {$strFilterByCouponTypes}";
                }

                $coupons = DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    AND c.coupon_type NOT IN ('FAQ','Saving Tips')
                    {$queryFilterByCouponTypes}

                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 100
                    "));
            }


            $couponsTypeFAQ = DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.coupon_type = 'FAQ' OR c.coupon_type = 'Saving Tips')
                    ORDER BY top_order ASC 
                    "
                ));

            if(!empty($couponsTypeFAQ)){
                foreach ($couponsTypeFAQ as $item) {
                    if($item->type == 'FAQ'){
                        array_push($customFAQ, $item);
                    }elseif ($item->type == 'Saving Tips'){
                        array_push($customSavingTip, $item);
                    }
                }
            }

            $storeUrl = $store['store_url'];
            $childStores = DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
            ));

        }

        $data['store'] = $store;
        $data['store']['name'] = $store['name'];
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];
        $data['store']['customFAQ'] = (array)$customFAQ;
        $data['store']['customSavingTip'] = (array)$customSavingTip;

        if (empty($store)) {
            if (strpos($storeAlias, '-coupons') !== FALSE) {
                $rm = str_replace('-coupons', '', $request->path());
                //return redirect($rm, 301);
            } else {
                // return response(view('errors.404'), 404);
                response('not found', 404);
            }
        }

        $re = $request->input('c');
        if (!empty($re) && !empty($data['store']['coupons'])) {
            $couponGo = $re['link'];
            $getCodeCoupon = Cache::remember('getCodeCoupon_' . $couponGo, 60, function () use ($couponGo) {
                return DB::select("SELECT c.id,  c.title,  c.currency,  c.exclusive,  c.description,  c.expire_date,  c.comment_count,  c.discount,  c.comment_count,  c.discount,  c.coupon_type AS type,  c.coupon_code AS code, coupon_type AS type,  c.sticky,  p.foreign_key_right AS go, c.latest_comments FROM coupons AS c INNER JOIN properties AS p ON p.foreign_key_left = c.id AND p.key = 'coupon' WHERE p.foreign_key_right = '$couponGo' LIMIT 1");
            });
            if (!empty($getCodeCoupon)) {
                array_unshift($data['store']['coupons'], $getCodeCoupon);
            }
        }

        $data['store']['couponType'] = [
            ['coupon_type' => 'All'],
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];

        // $data['trendingStore'] = Cache::remember('random_trend_stores_' . $storeId, 60 * 24, function () {
        //     return $this->randomLatestStores( 20);
        // });

        $data = $this->__getSEOConfig($data);
        // $data['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);

        $allowFAQs = $this->allowDisplayFAQs();
        $prn = Cache::remember('allowFAQs_'.$data['store']['alias'],60*60*24*7, function() use ($allowFAQs, $data){ // cache 7 days
            return in_array($data['store']['store_url'], $allowFAQs);
        });

        if( (empty($data['store']['note']) && !empty($data['store']['coupons'][0])) || $prn === true ){
            $storeName = $data['store']['name'];
            $storeName = str_replace(['\'','"'],'[[',$storeName);
            $latestVerifiedCoupon = DB::select("select * from coupons where store_id='{$data['store']['id']}' AND status='published' AND verified > 0 order by created_at desc limit 3");
            $str = '';
            if(!empty($latestVerifiedCoupon)){
                foreach ($latestVerifiedCoupon as $item) {
                    $t = str_replace(['\'','"'], '', $item->title);
                    $str.= "<b>{$t}</b> <br/>";
                }
            }

            $storeName = str_replace(["[["],"'",$storeName);
            $questions = [
                'q1' => [
                    'question' => "💰 How much can I save with {$storeName}?",
                    'answer' => "You can save an average of 15% at checkout with one valid coupon."
                ],'q2' => [
                    'question' => "⌚ How often do they release new coupon codes?",
                    'answer' => "New coupons will be released throughout the month. You can especially find great coupons on big holidays like Black Friday, Halloween, Cyber Monday, and more. "
                ],'q3' => [
                    'question' => "🛒 What is the best valid coupon that you can use?",
                    'answer' => "To save your time, top 3 first coupons are usually verified by our team:<br> {$str}"
                ],'q4' => [
                    'question' => "📩 Can I submit a $storeName?",
                    'answer' => "We accept coupon code submissions for many stores. Please see our <a href='https://couponsplusdeals.com/contact-us' title='Contact Us'>Contact Page</a> for more details and to submit your discount. Thank you very much!"
                ],'q5' => [
                    'question' => "😃 Can I use more than one  $storeName for my order?",
                    'answer' => "You can only use one coupon code per order. You should apply the code that gives you the best discount."
                ],
            ];
            $ignoreDisplayFAQs = ['0ce5e6c0-67dd-11e7-b0e0-db5d408b72d2','606e3d20-8c69-11e7-b450-5509ceadf0a0','355aff00-9466-11e7-a659-816afb3bd62b','7d18f8a0-7ac1-11e7-b1ee-ebf9ed8aed4b','fe284490-17a8-11e9-be37-fbc36c865246','55c4629f-1c38-4010-b62b-5e4461af48f5','5565c173-9024-4167-a631-4a8061af48f5','0aa56130-f65d-11e7-b4b2-bf7fc7bfb5e1','739b11c0-0285-11e8-8e24-912647ba793a','b0a39890-5f2c-11e7-9125-898990ce5f51','7b0a1cf0-679e-11e8-934b-fb675b2105b6','4033bc20-8cce-11e8-a443-09567e02f6f0','b955bb70-6205-11e7-8345-398949b6be67','3cf69930-a36b-11e7-a339-251d26a01b45','bf322070-7da7-11e7-9fc0-01785a3818a4','5b373940-02a4-11e8-b946-1f519939ee8c','5b23d900-8cce-11e8-9306-138acd29125a','d93335f0-714d-11e7-b074-6768eeb6b7e4','08fa0520-724c-11e9-864d-93a88f0d18a2','5565c033-4da4-4e71-bb8b-4a7d61af48f5','7e2cfbc0-bd8b-11e7-83fa-bbdb87d704ee','5565c08d-8fb4-4896-843a-4a8261af48f5','ec64dcf0-5f23-11e7-bc9a-0978980e4827','6583eb40-7150-11e7-a6ec-61e3003b4dec','d756c8a0-17b0-11e9-b6f1-3f1b5612c656','79af7a90-7695-11e7-bb54-d99699607878','b528de70-9228-11e7-8d80-cfd0cb4bdba8','aa6cbfb0-61f8-11e7-9dbe-37e191c6b286','b9c2e780-729f-11e7-8ea8-256ccbe28865','77ec1730-8cd3-11e8-8504-6b0a3772a60f','b6dc8570-2c14-11e8-8b8a-2f938e5c7c46','cf710d00-8cbe-11e9-9e4f-3533869f91ac','a6630800-8cce-11e8-b829-d14e09274964','96b54480-86ad-11e9-a9d2-11a930d81b39','b5e28800-5f29-11e7-af70-d9570754e0cb','22067ed0-f65a-11e7-879d-9d859cd81450','defd9df0-809b-11e7-8856-b328a8d6ae3d','d7eb6330-8c19-11e9-91a3-89a01b3f0bc9','98b58110-a36c-11e7-b4f1-298889b844d2','ef4e9260-eed7-11e8-b03b-713748ff605c','06d03bb0-eed8-11e8-add7-b5b8cc42b6d0','6212d710-8cd3-11e8-a566-b5dea56c0b0a','7946d010-6672-11e8-8d2d-47b2451cc3d5','a0f5f6d0-f65c-11e7-a5a9-61f8543ddceb','89ab2e50-33a5-11e9-ba1b-b9e21b93ce60','fed63670-8255-11e7-afa7-79886f5a26de','8e0903e0-3f41-11e8-9a01-15fd4cb2317a','25095e80-f658-11e7-b1d7-a7596719cf11','b9486d00-60b3-11e9-8f34-256e1259cc57','a77abe90-cd63-11e7-be3d-59d2eb36d2bd'];
            $data['questions'] = [];
            if(!in_array($data['store']['id'], $ignoreDisplayFAQs)){
                $data['questions'] = $questions;
            }

        }
        $data['aliasAMP'] = $storeAlias;

        return json_encode($data);
    }

    public function indexAMP(Re $request, $alias) {
        $storeAlias = $alias;
        $data = [];
        $storeCacheKey = 'store_' . $alias;
        $store = Cache::remember($storeCacheKey, 10080, function () use ($storeAlias) {
            return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order','social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();
        });
        if ($store && $store->note != 'ngach' && strpos($storeAlias, '-coupons') === false && strpos($request->path(), 'coupon-detail') === false) {
            if ($this->checkStringInLastPosition($storeAlias, '-coupons') === false) {
                return redirect(url('/amp/' . $storeAlias . '-coupons'), 301);
            }
        }
        if (!$store) {
            $storeAlias = str_replace('-coupons', '', $storeAlias);
            $store = Cache::remember('store_' . $storeAlias, 60 * 24 * 7, function () use ($storeAlias) {
                return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url', 'has_order','social_links','review_links')
                    ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                    ->where('stores.alias', '=', $storeAlias)
                    ->where('stores.countrycode', '=', 'US')
                    ->where('stores.status', '=', 'published')
                    ->first();
            });
        }
        $store = (array)$store;
        $coupons = [];
        $childStores = [];

        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['name'] = $storeName;

            $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                return DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                ));
            });

            $storeUrl = $store['store_url'];
            $childStores = Cache::remember('childStores_' . $storeId, 60 * 24, function () use ($storeAlias, $storeUrl) {
                return DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
                ));
            });
        }

        $data['store'] = $store;
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];

        if (empty($store)) {
            /* If not found */
            if (strpos($storeAlias, '-coupons') !== FALSE) {
                $rm = str_replace('-coupons', '', $request->path());
                return redirect($rm, 301);
            } else {
                return response(view('errors.404'), 404);
            }
        }

        $data['store']['couponType'] = [
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];

        $data['trendingStore'] = Cache::remember('random_trend_stores_' . $storeId, 60 * 24, function () {
            return $this->randomLatestStores(20);
        });

        $data = $this->__getSEOConfig($data);
        $data['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);

        $allowFAQs = $this->allowDisplayFAQs();
        $prn = Cache::remember('allowFAQs_'.$data['store']['alias'],60*60*24*7, function() use ($allowFAQs, $data){ // cache 7 days
            return in_array($data['store']['store_url'], $allowFAQs);
        });

        if( (empty($data['store']['note']) && !empty($data['store']['coupons'][0])) || $prn === true ){
            $storeName = $data['store']['name'];
            $storeName = str_replace(['\'','"'],'[[',$storeName);
            $latestVerifiedCoupon = DB::select("select * from coupons where store_id='{$data['store']['id']}' AND status='published' AND verified > 0 order by created_at desc limit 3");
            $str = '';
            if(!empty($latestVerifiedCoupon)){
                foreach ($latestVerifiedCoupon as $item) {
                    $t = str_replace(['\'','"'], '', $item->title);
                    $str.= "<b>{$t}</b> <br/>";
                }
            }
            $questions = [
                'q1' => [
                    'question' => "💰 How much can I save with {$storeName}?",
                    'answer' => "You can save an average of 15% at checkout with one valid coupon."
                ],'q2' => [
                    'question' => "⌚ How often do they release new coupon codes?",
                    'answer' => "New coupons will be released throughout the month. You can especially find great coupons on big holidays like Black Friday, Halloween, Cyber Monday, and more. "
                ],'q3' => [
                    'question' => "🛒 What is the best valid coupon that you can use?",
                    'answer' => "To save your time, top 3 first coupons are usually verified by our team:<br> {$str}"
                ],'q4' => [
                    'question' => "📩 Can I submit a $storeName?",
                    'answer' => "We accept coupon code submissions for many stores. Please see our <a href='https://couponsplusdeals.com/contact-us' title='Contact Us'>Contact Page</a> for more details and to submit your discount. Thank you very much!"
                ],'q5' => [
                    'question' => "😃 Can I use more than one  $storeName for my order?",
                    'answer' => "You can only use one coupon code per order. You should apply the code that gives you the best discount."
                ],
            ];
            $data['questions'] = $questions;
        }
        return view('amp-version.store-detail.store-detail-amp')->with($data);
    }

    public function getCouponDetailAMP($go) {
        $data['robots'] = 'noindex,nofollow';
        $data['couponGo'] = $go;
        $property = (Array)collect(\DB::select("select * from properties where foreign_key_right = '$go'"))->first();
        if (empty($property['foreign_key_left'])) return response(view('errors.404'), 404);

        $fkl = $property['foreign_key_left'];
        $coupon = (Array)(collect(\DB::select("select title,description,coupon_code,store_id from coupons where id='$fkl'"))->first());
        if (empty($coupon)) return response(view('errors.404'), 404);

        $storeId = $coupon['store_id'];
        $data['coupon'] = $coupon;
        $data['store'] = (Array)collect(\DB::select("select name,alias,logo,store_url from stores where id='$storeId'"))->first();

        return view('amp-version.store-detail.coupon-detail-amp')->with($data);
    }

    public function __getSEOConfig($data) {
        $greatestValue = 0;$curr = '';
        foreach ($data['store']['coupons'] as $c) {
            if(!empty($c->discount)){
                if($c->discount > $greatestValue){
                    $greatestValue = $c->discount;
                    $curr = $c->currency;
                }
            }
        }
        $maxDiscountVal = null;
        if($curr != '' && $greatestValue > 0){
            if($curr == '%') $maxDiscountVal = $greatestValue.$curr;
            elseif ($curr == '$') $maxDiscountVal = $curr.$greatestValue;
        }

        $month = date('F');
        $year = date('Y');
        echo print_r($data['store']);
        $storeName = $data['store']['name'];
        $data['seoConfig'] = [
            'title' => '',
            'desc' => '',
            'keyword' => "$storeName coupon codes, $storeName coupon, $storeName discount, $storeName discount codes, $storeName promo codes",
        ];
        if($maxDiscountVal) $maxDiscount = $maxDiscountVal;
        else {
            $ar = [30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95];
            $ak = array_rand($ar);
            $maxDiscount = $ar[$ak];
        }

        $store_domain = str_replace(['http://', 'https://', 'www.'], '', $data['store']['store_url']);
        if($greatestValue == 0)
            $seoTitle = "$maxDiscount% Off $store_domain Coupons & Promo Codes, " . date("F Y");
        else
            $seoTitle = "$maxDiscount Off $store_domain Coupons & Promo Codes, " . date("F Y");
        $data['seoConfig']['title'] = $seoTitle;

        $descriptionStruct = "Save with $storeName coupons and promo codes for $month, $year. Today's top $storeName discount";
        if (sizeof($data['store']['coupons']) > 0) {
            $firstCp = $data['store']['coupons'][0];
            $couponTitle = $firstCp->title;
            $descriptionStruct .= ': ' . $couponTitle;
        }
        $data['seoConfig']['desc'] = $descriptionStruct;
        return $data;
    }

    public function _getSEOConfigForPopupGetCode($countryCode, $data) {
        $seoConfig = Cache::remember('home_seo_config', 60 * 24 * 365, function () use ($countryCode) {
            return DB::select("select * from seo_configs where countrycode='$countryCode'");
        });
        if (!empty($seoConfig)) {
            $rs = [];
            $title = '';
            $metaDescription = '';
            $metaKeyword = '';
            $siteName = '';
            $siteDesc = '';
            $storeHeaderH1 = '';
            $storeHeaderP = '';
            $disableNoindex = '';
            $seo_defaultStoreTitle = '';
            $seo_defaultStoreMetaDescription = '';
            $seo_defaultStoreMetaKeyword = '';
            $seo_defaultH1Store = '';
            $seo_defaultPStore = '';
            foreach ($seoConfig as $s) {
                if ($s->option_name == 'seo_storeTitle') {
                    $title = $s->option_value;
                }
                if ($s->option_name == 'seo_storeDesc') {
                    $metaDescription = $s->option_value;
                }
                if ($s->option_name == 'seo_storeKeyword') {
                    $metaKeyword = $s->option_value;
                }
                if ($s->option_name == 'seo_siteName') {
                    $siteName = $s->option_value;
                }
                if ($s->option_name == 'seo_siteDescription') {
                    $siteDesc = $s->option_value;
                }
                if ($s->option_name == 'seo_storeH1') {
                    $storeHeaderH1 = $s->option_value;
                }
                if ($s->option_name == 'seo_storeP') {
                    $storeHeaderP = $s->option_value;
                }
                if ($s->option_name == 'seo_disableStoreNoIndex') {
                    $disableNoindex = $s->option_value;
                }

                if ($s->option_name == 'seo_defaultStoreTitle') {
                    $seo_defaultStoreTitle = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultStoreMetaDescription') {
                    $seo_defaultStoreMetaDescription = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultStoreMetaKeyword') {
                    $seo_defaultStoreMetaKeyword = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultH1Store') {
                    $seo_defaultH1Store = $s->option_value;
                }
                if ($s->option_name == 'seo_defaultPStore') {
                    $seo_defaultPStore = $s->option_value;
                }
            }
            if (isset($disableNoindex)) {
                $rs['disableNoindex'] = $disableNoindex;
            }

            $storeName = $data['store']['name'];
            if (sizeof($data['store']['coupons']) > 0) {
                $firstCp = $data['store']['coupons'][0];
                $firstCp = (array)$firstCp;
                $couponTitle = $firstCp['title'];
                $couponDiscount = $firstCp['discount'];
            } else {
                $couponTitle = '';
                $couponDiscount = '';
            }

            $configSelfSeoTitle = (isset($data['store']['meta_title']) && $data['store']['meta_title']) ? $data['store']['meta_title'] : null;
            $configSelfSeoDesc = (isset($data['store']['meta_desc']) && $data['store']['meta_desc']) ? $data['store']['meta_desc'] : null;
            $upToCashBack = sizeof($data['store']['cash_back_json']) > 0 ? (!empty($data['store']['cash_back_json'][0]['cash_back_percent']) ? $data['store']['cash_back_json'][0]['cash_back_percent'] . '%' : $data['store']['cash_back_json'][0]['currency'] . $data['store']['cash_back_json'][0]['cash_back']) : '';
            if (isset($title)) {
                if (!$couponDiscount) {
                    $rs['title'] = $this->seoConvert($configSelfSeoTitle ? $configSelfSeoTitle : $seo_defaultStoreTitle, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack, true);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['title'] = $this->seoConvert($configSelfSeoTitle ? $configSelfSeoTitle : $title, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack, true);
                }
            }
            if (isset($metaDescription)) {
                if (!$couponDiscount) {
                    $rs['desc'] = $this->seoConvert($configSelfSeoDesc ? $configSelfSeoDesc : $seo_defaultStoreMetaDescription, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['desc'] = $this->seoConvert($configSelfSeoDesc ? $configSelfSeoDesc : $metaDescription, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($metaKeyword)) {
                if (!$couponDiscount) {
                    $rs['keyword'] = $this->seoConvert($seo_defaultStoreMetaKeyword, $siteName, $siteDesc, $storeName,
                        $couponTitle, $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['keyword'] = $this->seoConvert($metaKeyword, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($storeHeaderH1)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderH1'] = $this->seoConvert($seo_defaultH1Store, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['storeHeaderH1'] = $this->seoConvert($storeHeaderH1, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            if (isset($storeHeaderP)) {
                if (!$couponDiscount) {
                    $rs['storeHeaderP'] = $this->seoConvert($seo_defaultPStore, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount, $upToCashBack);
                } else {
                    $firstCp = $data['store']['coupons'][0];
                    $firstCp = (array)$firstCp;
                    $rs['storeHeaderP'] = $this->seoConvert($storeHeaderP, $siteName, $siteDesc, $storeName, $couponTitle,
                        $couponDiscount . $firstCp['currency'], $upToCashBack);
                }
            }
            $data['seoConfig'] = $rs;
            if (isset($siteName)) {
                $data['siteName'] = $siteName;
            }

            $isOpenPopup = strpos($_SERVER['REQUEST_URI'], '?c=');
            if ($isOpenPopup) {
                $couponFKR = substr($_SERVER['REQUEST_URI'], $isOpenPopup + 3, 6);
                $foundCp = $this->_submitHTTPGet(config('config.api_url') . 'properties/?where[foreignKeyRight]=' . $couponFKR
                    . '&findType=findOne&where[key]=coupon' . '&attributes[]=foreignKeyLeft', []);
                $openingCoupon = $this->_submitHTTPGet(config('config.api_url') . 'coupons/?where[id]=' . $foundCp['foreignKeyLeft']
                    . '&findType=findOne', []);
                $openingCouponTitle = $openingCoupon['title'];
                $openingCouponDesc = $openingCoupon['description'];

                $data['seoConfig']['originTitle'] = $data['seoConfig']['title'];
                $data['seoConfig']['originDesc'] = $data['seoConfig']['desc'];
                $data['seoConfig']['title'] = $openingCouponTitle;
                $data['seoConfig']['desc'] = $openingCouponDesc;
            }
        }
        return $data;
    }

    public function indexNewCouponStruct(Re $request, $couponGo, $couponTitle, $alias = '') {
        $storeAlias = strtolower($alias);
        if (!empty($_GET['update-coupon'])) {
            $storeAlias = $alias;
            $dt = $this->indexForUpdateCoupon($storeAlias);
            return view('storeDetailForUpdateCoupon')->with($dt);
        }

        $dataSeo = [
            'seo' => [
                'title' => 'Discount Voucher Title index controller overwrite',
                'keywords' => 'Discount Voucher keywords index controller overwrite',
                'description' => 'Discount Voucher description index controller overwrite',
                'image' => ''
            ]
        ];

        $dataPage = ['dcCount' => '9,419'];

        if (!Session::has('coupon_type_' . $storeAlias)) Session::put('coupon_type_' . $storeAlias, []);
        $store = Cache::remember('store_' . $storeAlias, 10080, function () use ($storeAlias) {
            return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url','social_links','review_links')
                ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                ->where('stores.alias', '=', $storeAlias)
                ->where('stores.countrycode', '=', 'US')
                ->where('stores.status', '=', 'published')
                ->first();
        });
        /* neu la store bo */
        if ($store && $store->note != 'ngach' && strpos($storeAlias, '-coupons') === false && strpos($request->path(), 'coupon-detail') === false) {
            if ($this->checkStringInLastPosition($storeAlias, '-coupons') === false) {
                return redirect(url('/' . $storeAlias . '-coupons'));
            }
        }
        if (!$store) {
            $storeAlias = str_replace('-coupons', '', $storeAlias);
            $store['originalStoreName'] = $store['name'];
            $store = Cache::remember('store_' . $storeAlias, 60 * 24 * 7, function () use ($storeAlias) {
                return DB::table('stores')->select('stores.id AS id', 'name', 'logo', 'social_image', 'store_url', 'alias', 'affiliate_url', 'categories_id', 'best_store', 'custom_keywords', 'coupon_count', 'description', 'short_description', 'head_description', 'properties.foreign_key_right AS go', 'meta_title', 'meta_desc', 'cash_back_json', 'cash_back_total', 'cash_back_term', 'sid_name', 'update_coupon_from', 'note', 'store_url','social_links','review_links')
                    ->leftJoin('properties', 'stores.id', '=', 'properties.foreign_key_left')
                    ->where('stores.alias', '=', $storeAlias)
                    ->where('stores.countrycode', '=', 'US')
                    ->where('stores.status', '=', 'published')
                    ->first();
            });
        }
        $store = (array)$store;
        $coupons = [];
        $childStores = [];
        $expiredCoupons = [];
        if ($store) {
            $storeId = $store['id'];
            $storeName = $this->nameWithKeyword($store['name']);
            $store['name'] = $storeName;
            $coupons = Cache::remember('coupons_' . $storeId, 30, function () use ($storeId) {
                return DB::select(DB::raw(
                    "SELECT c.id,c.title,c.currency,c.exclusive,c.description,c.created_at,c.expire_date,c.discount,c.coupon_code AS code,c.coupon_type AS type,c.coupon_image AS image,c.sticky,c.verified,c.comment_count,c.latest_comments,c.number_used,c.cash_back,c.note,c.top_order,p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE
                        WHEN c.sticky = 'top' THEN 5
                        WHEN c.sticky = 'hot' THEN 4
                        WHEN c.verified = 1 THEN 3
                        WHEN c.sticky IS NULL THEN 2
                        WHEN c.sticky = 'none' THEN 1
                        WHEN c.sticky = 'pending' THEN 0
                    END DESC,
                    CASE
                        WHEN c.coupon_type='Coupon Code' THEN 1
                        WHEN c.coupon_type='Deal Type' THEN 2
                        WHEN c.coupon_type='Great Offer' THEN 3
                    END ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 25
                    "
                ));
            });
            $storeUrl = $store['store_url'];
            $childStores = Cache::remember('childStores_' . $storeId, 60 * 24, function () use ($storeAlias, $storeUrl) {
                return DB::select(DB::raw(
                    "SELECT name,alias FROM stores where store_url='$storeUrl' AND countrycode='US' AND alias != '$storeAlias' AND status='published'"
                ));
            });
        }

        $data['store'] = $store;
        $data['store']['coupons'] = (array)$coupons;
        $data['store']['expiredCoupons'] = (array)$expiredCoupons;
        $data['store']['childStores'] = (array)$childStores;
        $data['store']['relateStores'] = [];

        if (empty($store)) {
            return response(view('errors.404'), 404);
        }

        $re = $request->input('c');
        if (!empty($re) && !empty($data['store']['coupons'])) {
            $getCodeCoupon = $this->_submitHTTPGet(config('config.api_url') . 'coupons/getCouponWithLinkGo/' . $re . '/', [
                'c_location' => config('config.location')
            ]);
            if (!empty($getCodeCoupon)) {
                array_unshift($data['store']['coupons'], $getCodeCoupon);
            }
        }
        /*  */
        Session::forget('store-detail-more-' . $data['store']['id']);
        if (Session::has('store-detail-more-' . $data['store']['id'])) {
            $limit = intval(Session::get('store-detail-more-' . $data['store']['id']));
            $result = $this->_submitHTTPPost(config('config.api_url') . 'stores/storeDetailV2ShowMore/', [
                'storeId' => $data['store']['id'],
                'c_location' => config('config.location'),
                'c_limit' => $limit,
                'c_offset' => 20,
                'couponTypes' => Session::get('coupon_type_' . $storeAlias)
            ]);

            if (!empty($result) && $result['code'] == 0) $data['store']['coupons'] = array_merge($data['store']['coupons'], $result['data']);
        }

        if (Session::has('user.id')) {
            $query = '?';
            $query .= 'uuids[]=' . $data['store']['id'] . '&';
            if (sizeof($data['store']['coupons'])) {
                foreach ($data['store']['coupons'] as $s) {
                    $query .= 'uuids[]=' . $s['id'] . '&';
                }
            }
            $data['likes'] = $this->_submitHTTPGet(config('config.api_url') . 'likes/getLikes' . $query . 'userId=' . Session::get('user.id'), ['c_location' => config('config.location')]);
            if (sizeof($data['store']['relateStores'])) {
                foreach ($data['store']['relateStores'] as $s) {
                    $query .= 'uuids[]=' . $s['id'] . '&';
                }
            }
            $query .= 'userId=' . Session::get('user.id');
            $data['favourites'] = $this->_submitHTTPGet(config('config.api_url') . 'favourites/getFavourites' . $query, ['c_location' => config('config.location')]);
        }

        $data = $this->_getSEOConfigForPopupGetCode(config('config.location'), $data);

        if (!empty($request->input('c'))) $couponGo = $request->input('c');
        if ($couponGo) {
            //title
            $getCgo = Cache::remember('coupon_title_' . $couponGo , 60 * 24, function () use ($couponGo) {
                return DB::select("SELECT c.title FROM coupons c LEFT JOIN properties p ON c.id = p.foreign_key_left WHERE p.foreign_key_right = '{$couponGo}' LIMIT 1");
            });
            if (!empty($getCgo[0]))
                $getCgo = $getCgo[0];
            else
                return redirect("/$storeAlias"); // return go to store when not found coupon go
            if (!empty($getCgo->title)) $data['seoConfig']['title'] = $getCgo->title;
            //end title
        }

        $allData = array_merge($dataPage, $dataSeo, $data);
        $allData['siteName'] = env('SITE_NAME', '');
        $allData['store']['couponType'] = [
            ['coupon_type' => 'Coupon Code'],
            ['coupon_type' => 'Deal Type'],
            ['coupon_type' => 'Great Offer']
        ];
        $allData['store']['countCouponVerified'] = 10;
        $allData['store']['todayCoupon'] = 2;
        $allData['store']['expiredCoupons'] = [];
        $allData['googleAdsense'] = AdsHelper::allowDisplayAdsenseAndAFS($storeAlias);
        return view('storeDetailNew')->with($allData);
    }

    public function getStores(Re $request) {
        if ($request->ajax()) {
            $keyword = $request->input('q');
            $data = $this->_submitHTTPGet(config('config.api_url') . 'stores/', [
                'where[$or][storeUrl][$ilike]' => '%' . $keyword . '%',
                'where[$or][name][$ilike]' => '%' . $keyword . '%',
                'where[status]' => 'published',
                'where[countrycode]' => config('config.location'),
                'attributes[]=id&attributes[]=name&attributes[]' => 'storeUrl',
                'c_location' => config('config.location')
            ]);
            return response()->json(['items' => $data]);
        }
        return response()->json(['items' => []]);
    }

    public function showMoreCoupons(Re $request) {
        $storeId = $request->input('storeId');
        $offset = !empty($request->input('offset')) ? (int)$request->input('offset') : 0;
        $store = $this->_submitHTTPGet(config('config.api_url') . 'stores/', [
            'where[id]' => $storeId,
            'findType' => 'findOne',
            'c_location' => config('config.location')
        ]);
        if ($request->ajax() && !empty($store)) {
            $data['data'] = DB::select(DB::raw(
                "SELECT 
                    c.id, c.title, c.currency, c.exclusive, c.description, c.created_at, c.expire_date, c.discount, c.coupon_code AS code, c.coupon_type AS type, c.coupon_image AS image, c.sticky, c.verified, c.comment_count, c.latest_comments, c.number_used, c.cash_back, c.note, c.top_order, p.foreign_key_right AS go
                    FROM coupons c
                    JOIN properties p ON c.id = p.foreign_key_left
                    WHERE c.store_id = '{$storeId}'
                    AND c.status = 'published'
                    AND (c.expire_date >= NOW() OR c.expire_date IS NULL)
                    ORDER BY 
                    CASE 
                        WHEN c.verified = 1 THEN 5 
                        WHEN c.sticky = 'top' THEN 4 
                        WHEN c.sticky = 'hot' THEN 3 
                        WHEN c.sticky = 'none' THEN 2 
                        WHEN c.sticky IS NULL THEN 1 
                    END DESC, 
                    c.coupon_type ASC,
                    c.top_order ASC,
                    c.created_at DESC,
                    c.title ASC
                    LIMIT 20
                    OFFSET {$offset}
                    "
            ));
            if (!empty($data)) {
                return response(view('elements.v2-parent-box-coupon', ['coupons' => $data['data']]));
            } else return response()->json(['status' => 'error', 'coupons' => []]);
        }
        return response()->json(['status' => 'error', 'coupons' => []]);
    }

    public function searchStores(Re $request) {
        if ($request->ajax()) {
            $keyword = $request->input('kw');
            $data = $this->_submitHTTPPost(config('config.api_url') . 'stores/search/', [
                'keyword' => $keyword,
                'c_location' => config('config.location')
            ]);
            return response()->json(['status' => 'success', 'items' => $data]);
        }
        return response()->json(['status' => 'error', 'items' => []]);
    }

    public function filterCoupon(Re $request) {
        if ($request->ajax()) {
            $params = $request->all();

            $sessionKey = 'coupon_type_' . $params['alias'];

            if($params['coupon_type'] == 'All'){
                if($params['checked'] === 'true'){
                    $filterCouponByType = ['All'];
                }else{
                    $filterCouponByType = [];
                }
            }else{
                $filterCouponByType = Session::has($sessionKey) ? Session::get($sessionKey) : [];
                if ($params['checked'] && !in_array($params['coupon_type'], $filterCouponByType)) {
                    array_push($filterCouponByType, $params['coupon_type']);
                } else
                    $filterCouponByType = array_diff($filterCouponByType, [$params['coupon_type']]);

                if (($key = array_search('All', $filterCouponByType)) !== false) {
                    unset($filterCouponByType[$key]);
                }

            }

            if(!empty($filterCouponByType)){
                Session::put($sessionKey, $filterCouponByType);
            }else{
                Session::forget($sessionKey);
            }

            return response()->json(['status' => 'success', 'data' => $filterCouponByType]);
        }
        return response()->json(['status' => 'error']);
    }

    public function requestCoupon(Re $request) {
        $params = $request->all();

        if(empty($params['storeId']) OR empty($params['storeName']) OR empty($params['detail']))
            return response(view('errors.404'), 404);

        $storeId = $params['storeId'];
        $storeName = $params['storeName'];
        $storeUrlDetail = $params['detail'];
        $obj = Store::find($storeId);
        if ($obj) {
            $obj->count_request_coupon = $obj->count_request_coupon + 1;
            $rs = $obj->save();
            Mail::send('emails.request-coupon', ['name' => $storeName, 'detail' => $storeUrlDetail, 'id' => $storeId], function ($message) {
                $message->to(env('MAIL_REQUEST_COUPON_TO', 'haiht369@gmail.com'), 'HaiHT')->subject('Find me coupon');
            });
        } else {
            $rs = false;
        }
        return json_encode($rs);
    }

    private function getAliasOfFather($storeAlias) {
        $check = DB::select("select id,store_url,note from stores where alias=? and countrycode='US' limit 1", [$storeAlias]);
        $findFather = DB::select("select id,name,alias,store_url,note from stores where store_url=? and countrycode='US' and id != ? and (note is null or note != 'ngach')", [$check[0]->store_url, $check[0]->id]);
        if (!empty($findFather)) {
            return $findFather[0]->alias;
        }
        return $storeAlias;
    }

    public function testAllowAjax(Re $request) {
        $url = $request->all()['url'];
        $hp = new HP();
        $html = $hp->getHtmlViaProxy($url);
        return $html;
    }

    public function updateCouponDPF(Re $request) {
        $params = $request->all();
        $storeId = $params['storeId'];
        $storeUrl = $params['storeUrl'];
        $updateCouponFrom = !empty($params['updateCouponFrom']) ? $params['updateCouponFrom'] : '';
        $hp = new HP();
        if (strpos($updateCouponFrom, 'dontpayfull.com') !== false) {
            $data = $this->getDontPayFull($updateCouponFrom);
            $result = $hp->getUniqueOnly($data, $storeId);
        } else {
            $updateCouponFrom = 'https://www.dontpayfull.com/at/' . $storeUrl;
            $data = $this->getDontPayFull($updateCouponFrom);
            $result = $hp->getUniqueOnly($data, $storeId);
        }
        return $result;
    }

    public function getDontPayFull($url) {
        $hp = new HP();
        $html = $hp->getHtmlViaProxy($url);
        $arr = [];
        
        foreach ($html->find('.obox') as $item) {
            $title = trim($item->find('h3', 0)->plaintext);
            if ($item->find('.odescription')) {
                $desc = trim($item->find('.odescription', 0)->plaintext);
            } else {
                $desc = '';
            }

            if ($item->find('.ocode', 0)) {
                $code = trim($item->find('.ocode', 0)->plaintext);
            } else {
                $code = '';
            }
            if ($item->find('.percent__label')) {
                $discountValue = trim($item->find('.percent__label', 0)->plaintext);
            } else {
                $discountValue = '';
            }
            $dataCpId = $item->{'data-id'};
            $verify = $item->find('.verified', 0) ? 1 : 0;

            $a = [];
            $a['title'] = $title;
            $a['desc'] = $desc;
            $a['code'] = $code;
            $a['discount'] = $discountValue;
            $a['data-id'] = $dataCpId;
            $a['verify'] = $verify;
            array_push($arr, $a);
        }
        return $arr;
    }

    public function clearCache($storeAlias) {
        $store = DB::table('stores')->where('alias', $storeAlias)->first(['id']);
        if ($store) {
            $keyStore = 'store_' . $storeAlias;
            $result['store'] = Cache::forget($keyStore);
            $result['coupon'] = Cache::forget('coupons_' . $store->id);
            $result['childStore'] = Cache::forget('childStores_' . $store->id);
            echo "<pre>";
            var_dump($result);
            die;
        } else {
            die('Alias not found');
        }
    }
}
