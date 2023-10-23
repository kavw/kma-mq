## Introduction

Here is an example of a kind of app which works with rabbitmq and clickhouse

## How to run

```
git clone git@github.com:kavw/kma-test.git kma-test
git clone https://github.com/kavw/kma-test.git kma-test
cd ./kma-test
chmod u+x ./dc
./dc
```
To see logs for the publisher and the consumer you may run the following
```
./dc logs -f
```
