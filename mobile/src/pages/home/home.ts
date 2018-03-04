import { Platform } from 'ionic-angular';
import { Component } from '@angular/core';
import { Network } from '@ionic-native/network';
import { AlertController } from 'ionic-angular';
import { Subscription } from 'rxjs/Subscription';
import { OpenNativeSettings } from '@ionic-native/open-native-settings';
import { CompetitionProvider } from '../../providers/competition/competition';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { SettingsPage } from '../settings/settings';
import { CompetitionsPage } from '../competitions/competitions';

@Component({
  selector: 'page-home',
  templateUrl: 'home.html'
})
export class HomePage {

  private competitions: Array<Object> = [];
  private apiError: Boolean = false;
  private displayNoResults: Boolean;
  private noNetwork: Boolean = false;
  private connected: Subscription;
  private disconnected: Subscription;
  private alert: any = null;
  private loading: Boolean = true;

  hpbuttons: Array<{title: string, link: any}>;

  constructor(private competitionProvider: CompetitionProvider, private network: Network, private platform: Platform,
    private alertCtrl: AlertController, private openNativeSettings: OpenNativeSettings, public navCtrl: NavController) {
    this.hpbuttons = [
      // {title: 'Judging Sign In', link: SettingsPage},
      {title: "Competitions", link: CompetitionsPage},
      // {title: 'Tournament Bracket', link: CompetitionsPage},
      // {title: 'Competition Documents', link: SettingsPage},
      {title: 'Scores', link: SettingsPage},
      // {title: 'Table Times', link: SettingsPage}
      {title: 'Teams', link: SettingsPage}
    ];

    this.noNetwork = !this.hasNetwork();
    if (!this.noNetwork) {
      this.getData();
    }
    this.loading = false;
  }

  buttonSelected(page){
    this.navCtrl.push(page.link);
  }

  ionViewDidEnter() {
    this.connected = this.network.onConnect().subscribe(data => {
      console.log(data);
      this.noNetwork = false;
      if (this.alert != null) {
        this.alert.dismiss();
        this.alert = null;
        this.getData();
      }
    }, error => {
      console.error(error);
    });

    this.disconnected = this.network.onDisconnect().subscribe(data => {
      console.log(data);
      this.noNetwork = true;
      this.loading = false;
      if (this.alert == null) {
        this.alert = this.alertCtrl.create({
          title: "Network Disconnected",
          subTitle: "You are offline, please connect to continue.",
          buttons: [{
            text: 'Open Network Settings',
            handler: () => {
              this.openNativeSettings.open('wifi');
            }
          }]
        });
        this.alert.present();
      }
    }, error => {
      console.error(error);
    });
  }

  ionViewWillLeave() {
    this.connected.unsubscribe();
    this.disconnected.unsubscribe();
  }

  private getData() {
    // TODO Clean up
    this.competitionProvider.getCompetitions().then(val => {
      this.competitions = val;
      if (this.competitions.length <= 0) {
        this.displayNoResults = true;
      }
      this.loading = false;
    }).catch(err => {
      console.error(err);
      this.apiError = true;
      this.alert = this.alertCtrl.create({
        title: 'Network Error',
        message: 'If you are connected to a local network, please enter the local server address.',
        inputs: [
          {
            name: 'address',
            placeholder: 'http://127.0.0.1'
          }
        ],
        buttons: [
          {
            text: 'Cancel',
            handler: () => {
              console.log('Canceled Address enter');
            }
          }, {
            text: 'Submit',
            handler: () => {

          }
            }
        ]
      });
    this.alert.present();
  });
}

  private hasNetwork() {
  let type = this.network.type;
  return this.platform.is('core') || type == "ethernet" || type == "wifi" || type == "2g" || type == "3g" || type == "4g" || type == "cellular";
}

  private isMobile() {
  return this.platform.is('cordova') || this.platform.is('android') ||
    this.platform.is('ios') || this.platform.is('mobile') ||
    this.platform.is('mobile') || this.platform.is('phablet') ||
    this.platform.is('tablet');
}

}
