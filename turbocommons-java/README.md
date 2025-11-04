# Publishing a Java Library to Maven Central

An interactive guide to creating and publishing your Java library from scratch using Gradle.

## Prerequisites


Before we begin, ensure you have the necessary tools installed and ready to go. These are the foundational components for building and signing your library.

    - Java Development Kit (JDK) Installed on your computer (JAVA_HOME and PATH environment variables correctly setup)
    - Gradle (See official site for info on how to install latest version)
    - GPG Command Line Tool. Required for signing artifacts. Install Gpg4win (Windows)

## Step 1: Create a Sonatype (Nexus) Account

Sonatype manages OSSRH (Open Source Software Repository Hosting), the gateway for getting your library into Maven Central. Your first step is to create an account to manage your library submissions. Navigate to the Sonatype JIRA page and create a new account. This account will be used to request your Group ID and to log in to the Nexus repository manager for releasing your library.

[More info here](https://central.sonatype.com)

## Step 2: Reserve Your Group ID

Your `groupId` is a unique namespace for your projects, typically following a reverse domain name convention (e.g., `io.github.yourusername`). You must formally request this namespace from Sonatype.

    - Log in to the [Sonatype page](https://central.sonatype.com/publishing/namespaces) and add your own namespace.

## Step 3: Generate GPG Keys

Maven Central requires all published files (artifacts) to be digitally signed with a GPG key. This proves that you are the author and that the files have not been tampered with.

    - Launch Kleopatra (Gpg4win) from your Start Menu.
    - Click on File → New Certificate.
    - Choose Create a personal OpenPGP key pair.
    - Enter your name and email address (use the same email as your OSSRH account).
    - Key type: RSA, Key size: 4096 bits (recommended)
    - Click Create Key.
    - Set a secure passphrase when prompted.
    - Kleopatra will generate the key and show a success message.
    - Right-click your new key and choose Export. Save the file as public-key.asc.
    - Right-click your key → Export (or backup) Secret Keys.
    - Save it securely for backup purposes. Without this you will not be able to publish your library any more
    - Right-click your key → Details → Generate revoke Certificate
    - Create and save a revoke certificate in case you need to revoke this key in the future

## Step 3.1: Publish Your Public Key

Upload your public key to a keyserver so Sonatype can verify your signatures.

    - On Kleopatra, Right-click your key Go to File → Publish to Server
    - Kleopatra will use the default keyserver: hkp://keys.gnupg.net
    - Confirm the export.
    - You only need to publish to one keyserver. Most synchronize globally

## Step 4: Set Up the Gradle Project

Now it's time to configure your project's build files. This tells Gradle what to build, how to sign it, and where to publish it.

Create or edit the `build.gradle.kts` at the root of your project:
```
plugins {
    `java-library`
    `maven-publish`
    signing
}

group = "io.github.yourusername"
version = "1.0.0"

repositories {
    mavenCentral()
}

java {
    withJavadocJar()
    withSourcesJar()
}

publishing {
    publications {
        create<MavenPublication>("mavenJava") {
            from(components["java"])

            pom {
                name.set("My Awesome Library")
                description.set("A short description of my library.")
                url.set("https://github.com/yourusername/your-repo")
                licenses {
                    license {
                        name.set("The Apache License, Version 2.0")
                        url.set("http://www.apache.org/licenses/LICENSE-2.0.txt")
                    }
                }
                developers {
                    developer {
                        id.set("your-id")
                        name.set("Your Name")
                        email.set("your.email@example.com")
                    }
                }
                scm {
                    connection.set("scm:git:git://github.com/yourusername/your-repo.git")
                    developerConnection.set("scm:git:ssh://github.com:yourusername/your-repo.git")
                    url.set("https://github.com/yourusername/your-repo/tree/main")
                }
            }
        }
    }
    repositories {
        maven {
            name = "sonatype"
            url = uri("https://s01.oss.sonatype.org/service/local/staging/deploy/maven2/")
            credentials {
                username = System.getenv("OSSRH_USERNAME")
                password = System.getenv("OSSRH_PASSWORD")
            }
        }
    }
}

signing {
    sign(publishing.publications["mavenJava"])
}
```

Create or edit the `gradle.properties` file in your user home directory (`~/.gradle/`) to securely store your credentials. **NEVER commit this file to version control.**:

```
# Sonatype Credentials (from Step 1)
OSSRH_USERNAME=your_sonatype_jira_username
OSSRH_PASSWORD=your_sonatype_jira_password

# GPG Signing Key Details (from Step 3)
signing.keyId=A1B2C3D4E5F6G7H8
signing.password=your_gpg_key_passphrase
signing.secretKeyRingFile=/Users/youruser/.gnupg/secring.gpg
```

## Step 5: Publish to a Staging Repository

With configuration complete, you can now run the `publish` task. This command compiles, tests, signs, and uploads your library to a private "staging" area on Sonatype's servers for final review.

```
./gradlew publish
```

If the command succeeds, you will see a `BUILD SUCCESSFUL` message. Your library is now staged and ready for the final release step.

## Step 6: Release to Maven Central!

This is the final, manual step. You need to log in to Sonatype Nexus, review your staged library, and promote it to the public Maven Central repository.

    - Log in to Sonatype Nexus
        Go to [s01.oss.sonatype.org](https://s01.oss.sonatype.org/) and log in with your JIRA credentials.

    - Find and Inspect Your Repository
        In the left menu, click "Staging Repositories". Find the repository with your Group ID. Select it to verify all files (.jar, -sources.jar, .pom, .asc signatures) are present.

    - Close the Repository
        With your repository selected, click the **Close** button. This triggers an automated validation process. Click Refresh to monitor its status. If it fails, check the "Activity" tab for errors.
    
    - Release!
        Once the repository is successfully "closed", the **Release** button will become active. Click it, confirm, and you're done!

### Congratulations!

Your library is now syncing to Maven Central. This can take anywhere from 10 minutes to a couple of hours. You can search for it on [Maven Central Search](https://search.maven.org/).