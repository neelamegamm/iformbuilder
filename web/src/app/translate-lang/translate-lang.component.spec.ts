import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TranslateLangComponent } from './translate-lang.component';

describe('TranslateLangComponent', () => {
  let component: TranslateLangComponent;
  let fixture: ComponentFixture<TranslateLangComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ TranslateLangComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(TranslateLangComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
