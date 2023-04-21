import { Component, OnInit } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
@Component({
  selector: 'app-translate-lang',
  templateUrl: './translate-lang.component.html',
  styleUrls: ['./translate-lang.component.scss']
})
export class TranslateLangComponent implements OnInit {

  constructor(public translate: TranslateService) {
    translate.addLangs(['en-US', 'fr-FR']);
    translate.setDefaultLang('en-US');

    const browserLang:any = translate.getBrowserLang();
    translate.use(browserLang.match(/fr|fr-FR/) ? 'fr-FR' : 'en-US');

    console.log('Browser Lang =', browserLang);
    console.log('Navigator Lang =', navigator.language);
    console.log('Current Lang =', translate.currentLang);
  }

  ngOnInit(): void {
  }

}
