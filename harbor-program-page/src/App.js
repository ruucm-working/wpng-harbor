import React, { Component } from 'react';
import charming from 'charming';
import imagesLoaded from 'imagesloaded';

class App extends Component {
  constructor(props) {
    super(props);
    document.documentElement.className='js';
    var supportsCssVars=function(){var e,t=document.createElement('style');return t.innerHTML='root: { --tmp-var: bold; }',document.head.appendChild(t),e=!!(window.CSS&&window.CSS.supports&&window.CSS.supports('font-weight','var(--tmp-var)')),t.parentNode.removeChild(t),e};supportsCssVars()||alert('Please view this demo in a modern browser that supports CSS Variables.');


  }
  componentDidMount() {
    const chars = ['$','%','#','@','&','(',')','=','*','/'];
    const charsTotal = chars.length;
    const getRandomInt = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;

    class Entry {
        constructor(el) {
            this.DOM = {el: el};
            this.DOM.image = this.DOM.el.querySelector('.content__img');
            this.DOM.title = {word: this.DOM.el.querySelector('.content__text')};
            charming(this.DOM.title.word);
            this.DOM.title.letters = Array.from(this.DOM.title.word.querySelectorAll('span')).sort(() => 0.5 - Math.random());
            this.DOM.title.letters.forEach(letter => letter.dataset.initial = letter.innerHTML);
            this.lettersTotal = this.DOM.title.letters.length;
            observer.observe(this.DOM.el);
        }
        enter(direction = 'down') {
            this.DOM.title.word.style.opacity = 1;

            this.timeouts = [];
            this.complete = false;
            let cnt = 0;
            this.DOM.title.letters.forEach((letter, pos) => { 
                let loopTimeout;
                const loop = () => {
                    letter.innerHTML = chars[getRandomInt(0,charsTotal-1)];
                    letter.style.color = ['#2c0baf','#03a9f4','#062d86'][getRandomInt(0,2)];
                    loopTimeout = setTimeout(loop, getRandomInt(75,150));
                    this.timeouts.push(loopTimeout);
                };
                loop();

                const timeout = setTimeout(() => {
                    clearTimeout(loopTimeout);
                    letter.innerHTML = letter.dataset.initial;
                    letter.style.color = '#934ae8';
                    ++cnt;
                    if ( cnt === this.lettersTotal ) {
                        this.complete = true;
                    }
                }, pos*80+400);

                this.timeouts.push(timeout);
            });
        }
        exit(direction = 'down') {
            this.DOM.title.word.style.opacity = 0;
            if ( this.complete ) return;
            for ( let i = 0, len = this.timeouts.length; i <= len - 1; ++i ) {
                clearTimeout(this.timeouts[i]);
            }
        }
    }

    let observer;
    let current = -1;
    let allentries = [];
    const sections = Array.from(document.querySelectorAll('.content__section'));
    // Preload all the images in the page..
  imagesLoaded(document.querySelectorAll('.content__img'), () => {
        document.body.classList.remove('loading');
        if ('IntersectionObserver' in window) {
            document.body.classList.add('ioapi');

            observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if ( entry.intersectionRatio > 0.5 ) {
                        const newcurrent = sections.indexOf(entry.target);
                        if ( newcurrent === current ) return;
                        const direction = newcurrent > current;
                        if (current >= 0 ) {
                            allentries[current].exit(direction ? 'down' : 'up');
                        }
                        allentries[newcurrent].enter(direction ? 'down' : 'up');
                        current = newcurrent;
                    }
                });
            }, { threshold: 0.5 });

            sections.forEach(section => allentries.push(new Entry(section)));
        }
    });
  }
  render() {
    return (
      <div className="App">
<svg className="hidden">
      <symbol id="icon-arrow" viewBox="0 0 24 24">
        <title>arrow</title>
        <polygon points="6.3,12.8 20.9,12.8 20.9,11.2 6.3,11.2 10.2,7.2 9,6 3.1,12 9,18 10.2,16.8 "/>
      </symbol>
      <symbol id="icon-drop" viewBox="0 0 24 24">
        <title>drop</title>
        <path d="M12,21c-3.6,0-6.6-3-6.6-6.6C5.4,11,10.8,4,11.4,3.2C11.6,3.1,11.8,3,12,3s0.4,0.1,0.6,0.3c0.6,0.8,6.1,7.8,6.1,11.2C18.6,18.1,15.6,21,12,21zM12,4.8c-1.8,2.4-5.2,7.4-5.2,9.6c0,2.9,2.3,5.2,5.2,5.2s5.2-2.3,5.2-5.2C17.2,12.2,13.8,7.3,12,4.8z"/><path d="M12,18.2c-0.4,0-0.7-0.3-0.7-0.7s0.3-0.7,0.7-0.7c1.3,0,2.4-1.1,2.4-2.4c0-0.4,0.3-0.7,0.7-0.7c0.4,0,0.7,0.3,0.7,0.7C15.8,16.5,14.1,18.2,12,18.2z"/>
      </symbol>
      <symbol id="icon-github" viewBox="0 0 32.6 31.8">
        <title>github</title>
        <path d="M16.3,0C7.3,0,0,7.3,0,16.3c0,7.2,4.7,13.3,11.1,15.5c0.8,0.1,1.1-0.4,1.1-0.8c0-0.4,0-1.4,0-2.8c-4.5,1-5.5-2.2-5.5-2.2c-0.7-1.9-1.8-2.4-1.8-2.4c-1.5-1,0.1-1,0.1-1c1.6,0.1,2.5,1.7,2.5,1.7c1.5,2.5,3.8,1.8,4.7,1.4c0.1-1.1,0.6-1.8,1-2.2c-3.6-0.4-7.4-1.8-7.4-8.1c0-1.8,0.6-3.2,1.7-4.4C7.4,10.7,6.8,9,7.7,6.8c0,0,1.4-0.4,4.5,1.7c1.3-0.4,2.7-0.5,4.1-0.5c1.4,0,2.8,0.2,4.1,0.5c3.1-2.1,4.5-1.7,4.5-1.7c0.9,2.2,0.3,3.9,0.2,4.3c1,1.1,1.7,2.6,1.7,4.4c0,6.3-3.8,7.6-7.4,8c0.6,0.5,1.1,1.5,1.1,3c0,2.2,0,3.9,0,4.5c0,0.4,0.3,0.9,1.1,0.8c6.5-2.2,11.1-8.3,11.1-15.5C32.6,7.3,25.3,0,16.3,0z"/>
      </symbol>
    </svg>
    <main>
      <div className="content">
  
        <p className="info">harbor-1 Data Visualization <a href="https://studioakademi.com/">Akademi</a></p>
      </div>
      <div className="content">
        <div className="content__section">
          <div className="content__main">
            <div className="row">
              <div className="col-6">harbor-1</div>
              <div className="col-6">
                프로그래밍을 처음 시작하는 문과생이나,
                데이터 시각화에 도전해보고 싶은 사람들을 대상으로 하는 클래스
              </div>
            </div>
          </div>
          <h2 className="content__text">harbor-1</h2>
        </div>
        <div className="content__section">
          <a className="content__link">
            <div className="row">
              <div className="col-3">
                <h1>개요</h1>

                <p>기간 : 3일 (36시간) 프로그램 (3/23, 24, 25)</p>
                <p>목표 : 자신만의 데이터 시각화 웹앱 만들기 (꾸미기, 데이터 처리하기)</p>
                <p>가격 : 15만원</p>
                <p>장소 : 윌로비 🤔</p>
                <p>타깃 : 코딩을 모르거나, 막 배우기 시작한 문과생</p>
                <p>준비물 : mac os 🤔 / github 계정 / sourcetree 설치</p>
              </div>
              <div className="col-9"><img className="content__img" src="img/set2/1.jpg" alt="img"/></div>
            </div>
          </a>
          <h2 className="content__text">개요</h2>
        </div>
        <div className="content__section">
          <a className="content__link">
            <div className="row">
              <div className="col-3">
                <h1>Intro</h1>
                <p>'퀘스트 해결' 방식의 학습법 + 퀘스트 소개</p>
                <h1>html</h1>
                <p>난이도가 아주 작은 문제들 10개 (실제 현업에서 쓰이는 git 방식 사용)</p>
                <h1>css</h1>
                <p>난이도가 아주 작은 문제들 10개 (실제 현업에서 쓰이는 git 방식 사용)</p>
                <h1>javascript</h1>
                <p>난이도가 아주 작은 문제들 10개 (실제 현업에서 쓰이는 git 방식 사용)</p>
              </div>
              <div className="col-9"><img className="content__img" src="img/set2/2.jpg" alt="img"/></div>
            </div>
          </a>
          <h2 className="content__text">프로그램 구성 - 1일차</h2>
        </div>
        <div className="content__section">
          <a className="content__link">
            <div className="row">
              <div className="col-3">
                <h1>데이터 가져오기</h1>
                <p>길을 못할 같이, 동력은 것이다. 인간의 온갖 할지라도 용감하고 들어 없으면 곳으로 싶이 우리 봄바람이다.</p>
                <h1>rechart.js 로 출력</h1>
                <p>길을 못할 같이, 동력은 것이다. 인간의 온갖 할지라도 용감하고 들어 없으면 곳으로 싶이 우리 봄바람이다.</p>
              </div>
              <div className="col-9"><img className="content__img" src="img/set2/2.jpg" alt="img"/></div>
            </div>
          </a>
          <h2 className="content__text">프로그램 구성 - 2일차</h2>
        </div>
        <div className="content__section">
          <a className="content__link">
            <div className="row">
              <div className="col-3">
                <h1>css(sass) 로 정해진 템플릿처럼 꾸미기</h1>
                <p>길을 못할 같이, 동력은 것이다. 인간의 온갖 할지라도 용감하고 들어 없으면 곳으로 싶이 우리 봄바람이다.</p>
              </div>
              <div className="col-9"><img className="content__img" src="img/set2/2.jpg" alt="img"/></div>
            </div>
          </a>
          <h2 className="content__text">프로그램 구성 - 3일차</h2>
        </div>
        <div className="content__section">
          <a className="content__link">
            <div className="row">
              <div className="col-3">
                <h1>진행원칙</h1>
                <p>기본적으로 팀 전체의 점수제로 들어가게되고,</p>
                <p>먼저 개인도 잘해야하지만, 팀별 점수도 있어서 못하는 친구도 끌어줘야하는 제도</p>
                <p>우수 개인과, 전원통과시 전원에게 수료 뱃지 제공</p>
              </div>
              <div className="col-9"><img className="content__img" src="img/set2/2.jpg" alt="img"/></div>
            </div>
          </a>
          <h2 className="content__text">리워드</h2>
        </div>
        <div className="content__section">
          <a className="content__link content__link--nobottom"><img className="content__img" src="img/set2/6.jpg" alt="img"/></a>
          <h2 className="content__text">지원하기!</h2>
        </div>
        <div className="content__section">
          <h2 className="content__text">Contact</h2>
          <div className="content__contact">
            <a href="#">부두공 02</a>
            <p><a href="#">010 3993 6177</a></p>
            <p><a href="#">ruucm@harbor.school</a></p>
          </div>
        </div>
      </div>
    </main>
      </div>
    );
  }
}

export default App;
