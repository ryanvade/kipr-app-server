import { Component } from '@angular/core';
import { AlertController } from 'ionic-angular';
import { StatusProvider } from '../../providers/status/status';
import { IonicPage } from 'ionic-angular';//, NavController, NavParams
import { SettingsProvider } from '../../providers/settings/settings';
import { BarcodeScanner } from '@ionic-native/barcode-scanner';//, BarcodeScannerOptions
import { Events } from 'ionic-angular';

/**
 * Generated class for the JudgingPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-settings',
  templateUrl: 'settings.html',
})
export class SettingsPage {
  serverName: String = '';
  judgingAuthToken: String = '';
  signInAuthToken: String = '';
  judgingEnabled: boolean = false;
  signInEnabled: boolean = false;

constructor(private settingsProvider: SettingsProvider, private status: StatusProvider, private barcodeScanner: BarcodeScanner, private alertCtrl: AlertController, public events: Events){
    this.getSettings();
  }

  async getSettings() {
    this.serverName = await this.settingsProvider.getServerName();
    this.judgingAuthToken = await this.settingsProvider.getAuthToken();
    this.signInAuthToken = await this.settingsProvider.getSignInAuthToken();

    if(this.judgingAuthToken == '' || this.judgingAuthToken == null) {
      this.judgingEnabled = false;
    }else {
      this.judgingEnabled = true;
    }
    console.log("Judging Enabled", this.judgingEnabled);
    if(this.signInAuthToken == '' || this.signInAuthToken == null) {
      this.signInEnabled = false;
    }else {
      this.signInEnabled = true;
    }
    console.log("SignIn Enabled", this.signInEnabled);
  }


disableSignIn() {
  this.signInEnabled = false;
  this.settingsProvider.setAuthToken(null);
  this.settingsProvider.setSignInCompetitionID(null);
  this.judgingAuthToken = "";
  this.events.publish('authentication:signin', false);
}

disableJudging() {
  this.judgingEnabled = false;
  this.settingsProvider.setSignInAuthToken(null);
  this.judgingAuthToken = "";
  this.events.publish('authentication:judging', false);
}

async scanForAuthToken(){
  this.barcodeScanner.scan().then(async (barcodeData) => {
    console.log(barcodeData.text);
    let valid = this.status.validateAuthToken(barcodeData.text, this.serverName);
    if(valid) {
      this.judgingAuthToken = barcodeData.text;
      this.settingsProvider.setAuthToken(barcodeData.text);
      this.judgingEnabled = true;
      this.events.publish('authentication:judging', true);
    }else {
      this.judgingAuthToken = '';
      this.settingsProvider.setAuthToken('');
      let alert = this.alertCtrl.create({
        title: 'Authorization Error',
        subTitle: 'Unable to authenticate with the server. Please try again.',
        buttons: ['OK']
      });
      alert.present();
    }
  }, (err) => {
    let alert = this.alertCtrl.create({
      title: 'Authorization Error',
      subTitle: 'Unable to read the QR code. Please try again.',
      buttons: ['OK']
    });
    alert.present();
    console.log(err);
    this.judgingEnabled = false;
    this.events.publish('authentication:judging', false);
  });
}

async scanForSignInAuthToken(){
  this.barcodeScanner.scan().then(async (barcodeData) => {
    console.log(barcodeData.text);
    let valid = this.status.validateAuthToken(barcodeData.text, this.serverName);
    let splitArray = barcodeData.text.split("|");

    if(valid) {
      this.judgingAuthToken = splitArray[0];
      this.settingsProvider.setSignInAuthToken(splitArray[0]);
      this.settingsProvider.setSignInCompetitionID(splitArray[1]);
      this.signInEnabled = true;
      this.events.publish('authentication:signin', true);
    }else {
      this.signInAuthToken = '';
      this.settingsProvider.setSignInAuthToken('');
      this.settingsProvider.setSignInCompetitionID('');
      let alert = this.alertCtrl.create({
        title: 'Authorization Error',
        subTitle: 'Unable to authenticate with the server. Please try again.',
        buttons: ['OK']
      });
      alert.present();
    }
  }, (err) => {
    let alert = this.alertCtrl.create({
      title: 'Authorization Error',
      subTitle: 'Unable to read the QR code. Please try again.',
      buttons: ['OK']
    });
    alert.present();
    console.log(err);
    this.signInEnabled = false;
    this.events.publish('authentication:signin', false);
  });
}
  ionViewDidLoad() {
    console.log('Loaded');
  }
}
