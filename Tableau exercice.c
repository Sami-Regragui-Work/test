#include<stdio.h>
#include<stdlib.h>

void challenge1(){
    int array[]={1,2,3,4,5};
    for (int i=0; i<sizeof(array)/sizeof(int); i++)
        printf("%i\n",array[i]);
}
void challenge2(){
    int n;
    char *array;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);

    array= (char *)malloc(n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (char *)malloc(n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        getchar();
        printf("Val%i: ",i+1);
        scanf("%c",&array[i]);
    }
    printf("Vous avez entrez\n");
    for (int i=0; i<n; i++){
        printf("Val%i: %c\t",i+1,array[i]);
    }
}
void challenge3(){
    int *array, n, sum=0;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);
    
    array= (int *)malloc(sizeof(int) * n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (int *)malloc(sizeof(int) * n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        printf("Val%i: ",i+1);
        scanf("%i",&array[i]);
        sum+=array[i];
    }
    printf("La sommes de ces valeurs est: %i", sum);
}

void challenge4(){
    int *array, n, max;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);
    
    array= (int *)malloc(sizeof(int) * n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (int *)malloc(sizeof(int) * n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        printf("Val%i: ",i+1);
        scanf("%i",&array[i]);
    }
    max=array[0];
    for (int i=1; i<n; i++){
        if (max<array[i])
            max=array[i];
    }
    printf("Le maximum dans ce tableau est: %i", max);
}

void challenge5(){
    int *array, n, min;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);
    
    array= (int *)malloc(sizeof(int) * n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (int *)malloc(sizeof(int) * n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        printf("Val%i: ",i+1);
        scanf("%i",&array[i]);
    }
    min=array[0];
    for (int i=1; i<n; i++){
        if (min>array[i])
            min=array[i];
    }
    printf("Le minimum dans ce tableau est: %i", min);
}
void challenge6(){
    int *array, n, coef;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);
    
    array= (int *)malloc(sizeof(int) * n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (int *)malloc(sizeof(int) * n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        printf("Val%i: ",i+1);
        scanf("%i",&array[i]);
    }
    printf("Donnez le facteur svp: ");
    scanf("%i", &coef);
    for (int i=0; i<n; i++){
        array[i]*=coef;
        printf("[%i]:%i \t",i+1 ,array[i]);
    }
}
void challenge7(){
    int *array, n, aux;
    printf("Combien des elements voulez-vous entrez dans le tableau?\n  ");
    scanf("%i",&n);
    
    array= (int *)malloc(sizeof(int) * n);
    while (array==NULL){
        printf("stockage insuffisant! re-entrez le nombre des elements\n");
        scanf("%i",&n);
        array= (int *)malloc(sizeof(int) * n);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n; i++){
        printf("Val%i: ",i+1);
        scanf("%i",&array[i]);
    }
    
    for (int i=0; i<n-1; i++)
        for (int j=i+1; j<n; j++)
            if (array[i]>array[j]){
                aux= array[i];
                array[i]= array[j];
                array[j]= aux;
            }
    for (int i=0; i<n; i++){
        printf("[%i]:%i \t",i+1 ,array[i]);
    }
}

void challenge8(){
    // since the question is leaving room for hard codding, I'm changing my way here
    char arrayOrg[]={'2','3','1','5','6','4','8','9','7','0'}, *arrayCp;
    //10 characters
    arrayCp=(char*)malloc(sizeof(arrayOrg));
    for (int i=0; i<10; i++){
        arrayCp[i]= arrayOrg[i];
        printf("original tableau contenu est: %c\tla copy est: %c\n", arrayOrg[i], arrayCp[i]);
    }
}

void challenge9(){
    char array[]={'2','3','1','5','6','4','8','9','7','0'}, aux;
    //10 characters
    int lastIndex= sizeof(array) - 1;
    for (int i=0; i<5; i++){
        aux=array[i];
        array[i]=array[lastIndex-i];
        array[lastIndex-i]=aux;
    }
    printf("le tableau inverse: ");
    for (int i=0; i<10; i++){
        printf("%c  ", array[i]);
    }
}

void challenge10(){

}

int main(){
    // challenge1();
    // challenge2();
    // challenge3();
    // challenge4();
    // challenge5();
    // challenge6();
    // challenge7();
    // challenge8();
    challenge9();
    
    return 0;
}