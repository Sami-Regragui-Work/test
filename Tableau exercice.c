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
    char *array, elem;
    int n;
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
    printf("Donnez l'element a chercher svp: ");
    getchar();
    scanf("%c", &elem);
    int i =0;
    while (array[i]!=elem && i<n){
        i++;
    }
    if (i==n) printf("l'element n'existe pas dans le tableu");
    else printf("l'element existe dans le tableu");
}

void challenge11(){
    char *array, ocur, remp;
    int n;
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
    printf("Donnez l'element a chercher svp: ");
    getchar();
    scanf("%c", &ocur);
    printf("Donnez le remplacement svp: ");
    getchar();
    scanf("%c", &remp);
    for (int i=0; i<n; i++){
        if (array[i]==ocur){
            array[i]=remp;
            printf("original tableau contenu est: %c  new contenu est: %c\n", ocur, array[i]);
        }
        else
            printf("original tableau contenu est: %c  new contenu est: %c\n", array[i], array[i]);
    }

}

void challenge12(){
    int *array, n;
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
    printf("les elements pairs sont:");
    for (int i=0; i<n; i++){
        if (!(array[i]%2))
            printf("  %i",array[i]);
    }

}

void challenge13(){
    int *array, n;
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
    printf("les elements pairs sont:");
    for (int i=0; i<n; i++){
        if (array[i]%2)
            printf("  %i",array[i]);
    }
}

void challenge14(){
    int *array, n, sum;
    float avg;
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
    avg=sum/n;
    printf("la moyenne est %.2f",avg);
}

void challenge15(){
    int n1, n2, N;
    char *array1, *array2, *array3;
    do{
        printf("Combien des elements voulez-vous entrez dans le premier tableau?\n  ");
        scanf("%i",&n1);
        array1= (char *)malloc(n1);
        while (array1==NULL){
            printf("stockage insuffisant! re-entrez le nombre des elements\n");
            scanf("%i",&n1);
            array1= (char *)malloc(n1);
        }
        printf("Combien des elements voulez-vous entrez dans le deuxieme tableau?\n  ");
        scanf("%i",&n2);
        array2= (char *)malloc(n2);
        while (array2==NULL){
            printf("stockage insuffisant! re-entrez le nombre des elements\n");
            scanf("%i",&n2);
            array2= (char *)malloc(n2);
        }
        N=n1+n2;
        array3= (char *)malloc(N);
        if (array3==NULL) printf("stockage insuffisant pour le tableau fusionne! re-entrez le nombre des elements\n");
    } while(array3==NULL);
    
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n1; i++){
        getchar();
        printf("Val%i: ",i+1);
        scanf("%c",&array1[i]);
    }
    printf("Donnez ces valeurs svp\n");
    for (int i=0; i<n2; i++){
        getchar();
        printf("Val%i: ",i+1);
        scanf("%c",&array2[i]);
    }
    int i=0;
    while (array3[N-1]!=array2[n2-1] || array3[n1-1]!=array1[n1-1]){
        array3[i]=array1[i];
        array3[i+n1]=array2[i];
        i++;
    }
    printf("le tableau fusionne\n");
    for (int i=0; i<N; i++){
        printf("%c  ",array3[i]);
    }
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
    // challenge9();
    // challenge10();
    // challenge11();
    // challenge12();
    // challenge13();
    // challenge14();
    challenge15();
    return 0;
}