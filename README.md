# Outline
<p>기존에 이미 AWS EC2 환경을 통해 운영하던 WordPress 블로그였지만, Docker 기반의 AWS Elastic Beanstalk 환경과 </p>
<p>CI/CD 학습을 위해 재구축하였습니다. </p>
<p>관련 학습에 대한 공부도 되었지만 기존에 알던 내용을 다시 한번 짚어보게 된 의미 있는 프로젝트였습니다. </p>
<p>아래 내용을 통해 구축 과정 및 내용을 소개드립니다. 😀</p>

***
## ▎development environment
- Front-end :
<span><img src="https://img.shields.io/badge/CSS-1572b6?style=flat&logo=css3&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/JavaScript-dbab09?style=flat&logo=javascript&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/jQuery-0769ad?style=flat&logo=jquery&logoColor=white"/></span>
- Back-end : 
<span><img src="https://img.shields.io/badge/PHP-777BB4?style=flat&logo=PHP&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=MySQL&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/NGINX-009639?style=flat&logo=NGINX&logoColor=white"/></span> 
- Platform : 
<span><img src="https://img.shields.io/badge/WordPress-21759B?style=flat&logo=WordPress&logoColor=white"/></span> 
- Deployment : 
<span><img src="https://img.shields.io/badge/AWS-232f3e?style=flat&logo=amazon-aws&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/Docker-2496ED?style=flat&logo=Docker&logoColor=white"/></span>
- Version Control : <span><img src="https://img.shields.io/badge/Git-f05032?style=flat&logo=git&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/GitHub-181717?style=flat&logo=github&logoColor=white"/></span>
<span><img src="https://img.shields.io/badge/CircleCI-343434?style=flat&logo=CircleCI&logoColor=white"/></span><br>

## ▎CI Workflow
[![image](https://user-images.githubusercontent.com/38343913/158019700-79c72e60-ae4f-4fdc-aa6e-1f6601cdc5e1.png)](#)
> <b>구성 설명</b>
>> <p>위 이미지는 개발 및 테스트된 내용을 remote repository에 업로드한 이후부터 EB 환경에서의 Deploy 과정까지를 나타냅니다.</p>
>>> 우선 S3와 EB에 대한 권한을 가진 IAM 사용자의 Access Key와 Secret Key, DockerHub에 이미지 업로드를 위한 계정 정보, EB 환경 등에 대한 환경변수 설정이 필요합니다.<br>
>>> 이후 remote repository로 업로드시 .circleci/config.yml의 workflow 설정에 따라 트리거가 실행됩니다.<br>
>> <p></p>
>> <p><b>Docker Image 빌드 및 업로드</b></p>
>>> workflow의 설정을 통해 테스트 환경을 Docker Image로 빌드 후 실행하여 테스트 코드를 진행합니다.<br>
>>> 테스트가 끝나면 운영 환경의 이미지를 빌드 후 DockerHub에 업로드합니다.<br>
>> <p></p>
>> <p><b>AWS Elastic Beanstalk 배포</b></p>
>>> EB 배포를 위해 <b>AWS Elastic Beanstalk용 명령줄 인터페이스(awsebcli)</b>를 설치합니다.<br>
>>> eb cli를 통해 필요한 입력값(region, platform, env name 등)을 넣어 배포를 진행합니다.<br>
>>> 배포시 Source Code는 zip 파일로 S3에 업로드되며 AWS Elastic Beanstalk에서 S3에 업로드된 zip 파일을 통해 배포하게 됩니다.<br>

## ▎AWS Service Architecture
[![image](https://user-images.githubusercontent.com/38343913/158030069-caa7ce37-59ab-4b41-9826-1efa5e81b1f8.png)](#)
> <b>구성 설명</b>
>> <p>위 이미지는 해당 AWS 서비스 환경의 구조를 나타냅니다.</p>
>> <p><b>EB 구성 및 접근 설정</b></p>
>>> 사용자는 도메인을 통해 해당 서비스에 접근할 수 있습니다.<br>
>>> SSL 접근을 위해 AWS Certificate Manager로 해당 도메인의 인증서를 발급받았습니다.<br>
>>> Route 53에 등록한 도메인과 인증서를 연결하였습니다.<br>
>>> EB 환경 내의 EC2 인스턴스 간의 auto scaling을 위해 ALB를 구성하였습니다.<br>
>>> 구성한 ALB와 Route 53에 등록한 도메인을 연결하였습니다.<br>
>>> 악성 접근을 차단하기 위해 WAF에 EB 환경의 ALB를 지정하여 구성하였습니다.<br>
>>> EB 환경을 알림을 통해 모니터링 하기 위해 CloudWatch 설정하였습니다.<br>
>> <p></p>
>> <p><b>DB 구성</b></p>
>>> EB 환경에서 배포시 EC2 인스턴스는 구성한 auto scaling으로 인해 가변합니다.<br>
>>> 그러므로 영속성을 가져야하는 DB를 RDS로 분리한 후 접속을 위해 별도의 보안 그룹을 지정합니다.<br>
>> <p></p>
>> <p><b>EC2 인스턴스 내부 구성</b></p>
>>> 생성된 EC2 인스턴스는 1개의 Nginx 컨테이너와 2개의 PHP 서비스 컨테이너로 구성하였습니다.<br>
>>> Nginx 컨테이너를 웹 서버로 지정하여 각 서비스 컨테이너를 연결하였습니다.<br>

## ▎Result
[![image](https://user-images.githubusercontent.com/38343913/158014722-9d3d9eda-71f3-4652-a852-8f6cb1a9d8f5.png)](https://jpro.blog)
> <a href="https://www.jpro.blog/">JPro Blog</a> 에서 이용해보기 😄

***
# ▎관련 정리 자료
- <a href="https://www.jpro.blog/?p=2220">Elastic Beanstalk 이란?</a>
- <a href="https://www.jpro.blog/?p=2147">Circle CI 사용법 (+Docker 서비스 빌드해보기)</a>
- <a href="https://www.jpro.blog/?p=353">WordPress - BackWPup backup 플러그인 사용해보기</a>

