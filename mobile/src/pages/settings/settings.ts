import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { BarcodeScanner } from '@ionic-native/barcode-scanner';
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
  barCodeData = null;
  constructor(public navCtrl: NavController, public navParams: NavParams, private barcodeScanner: BarcodeScanner){

  }

funct(){
  this.barcodeScanner.scan().then((barcodeData) => {
    console.log(barcodeData);
    this.barCodeData = barcodeData;
    console.log('Barcode read: ' + barcodeData);
  }, (err) => {
    console.log(err);
  });
}

  ionViewDidLoad() {
    console.log('Loaded');
  }
}
