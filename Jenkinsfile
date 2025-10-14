@Library("Shared_library") _

pipeline{
    agent {label "ec2agent"}
    
    stages{
        stage("code"){
            steps{
                echo "This is cloning the code"
                git url: "https://github.com/geekapt/Docker-php.git" , branch: "master"
                echo "Code cloned successfully.."
            }
        }
        stage("build"){
            steps{
                echo "This is building the code"
                sh "whoami"
                sh "docker build -t docker-php:latest ."
                sh "docker images | grep docker-php"
            }
        }
        stage("docker-hub-push"){
            steps{
                echo "Docker image is pushing to docker hub"
                withCredentials([usernamePassword(
                    'credentialsId':"dockerHubCred",
                    passwordVariable:"dockerHubPass",
                    usernameVariable:"dockerHubUser")]){
                sh "docker login -u ${env.dockerHubUser} -p ${env.dockerHubPass}"
                sh "docker image tag docker-php:latest ${env.dockerHubUser}/docker-php:latest"
                sh "docker push ${env.dockerHubUser}/docker-php:latest"
                }

            }
        }
        stage("test"){
            steps{
                echo "This is tesing the code"
            }
        }
        stage("deploy"){
            steps{
                echo "This is deploying the code"
                sh "docker compose down && docker compose up --build -d"
            }
        }
    }
}
