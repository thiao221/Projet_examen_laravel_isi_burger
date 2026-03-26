pipeline {
    agent any

    environment {
        DOCKER_IMAGE = "isi-burger-app:${env.BUILD_NUMBER}"
    }
        //test
    stages {
        stage('Pull du Code') {
            steps {
                // Récupère la branche spécifique demandée
                checkout scm
            }
        }
            //test
        stage('Installation Laravel') {
            steps {
                // On installe les dépendances PHP sans interaction
                sh 'composer install --no-interaction --prefer-dist --optimize-autoloader'
            }
        }

        stage('Build Image Docker') {
            steps {
                script {
                    // On construit l'image à partir du Dockerfile que tu as créé
                    sh "docker build -t ${DOCKER_IMAGE} ."
                }
            }
        }

        stage('Test & Nettoyage') {
            steps {
                echo "L'image ${DOCKER_IMAGE} est prête pour ISI BURGER !"
            }
        }
    }
}
