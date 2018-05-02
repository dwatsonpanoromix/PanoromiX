#Note :   There is a  mainpath='D:/FACT/'  you need to change
#The user selection of species:  Homo Sapiens, Mus musculus, Rattus norvegicus, Macaca Mulatta

###################################################################
#Description: gene set enrichment (over-representation method)
#Usage: DEpath(whatgene,pathlen,n.unique.allgenes,sym.base,pathway,FDR=F)
#Argument:
#whatgene: a target gene list
#pathlen: length of each gene set
#n.unique.allgenes: all unique symbols in the dataset 
#sym.base: gene-gene set frequency table
#pathway: gene set names
#FDR: if FDR=T, then run false discovery rate
#Value: enriched gene sets
#Details: over-representation method
#Note: 
#Authors: Ruoting Yang
#Last update 09/23/2014
###################################################################

DEpath<-function(geneset,pathlen,n.unique.allgenes,sym.base,pathway,FDR=F)
{whatgene=as.matrix(unique(na.omit(geneset[,'humanEntrez',drop=F])))  # source symbols in this cluster

  nwhatgene=length(whatgene)
    intersect.gene=colSums(sym.base[intersect(whatgene,rownames(sym.base)),])  # intersection of pathway and source set
  hyperg=phyper(intersect.gene-1,nwhatgene,n.unique.allgenes-nwhatgene,pathlen,lower.tail=FALSE) #hypergeometric test
 print(sum(hyperg<.05))
  if (FDR==T)  hyperg=p.adjust(hyperg,"fdr")  #FDR correction using BH method
 
 # print(cbind(pathway[hyperg<0.05 & intersect.gene>2],hyperg[hyperg<0.05 & intersect.gene>2],intersect.gene[hyperg<.05 & intersect.gene>2]))
  print(sum(hyperg<.05 & intersect.gene>2))
  plist=list()
# print(path$gene[hyperg<.05 & intersect.gene>2])
 print(intersect(path$gene[hyperg<.05 & intersect.gene>2][1],whatgene))
  plist=lapply(path$gene[hyperg<.05 & intersect.gene>2],function(x) intersect(x,whatgene))
  names(plist)=pathway[hyperg<.05 & intersect.gene>2]
  
  return(plist)      
}
overlapdist<-function(geneset)
{len=length(geneset$gene)
 B=matrix(rep(0,len*len),nrow=len)
 for (i in 1:(len-1))
   for (j in (i+1):len)
   {     c=length(unique(c(geneset$gene[[i]],geneset$gene[[j]])))
         B[j,i]=min(geneset$len[[i]],geneset$len[[j]])/(geneset$len[[i]]+geneset$len[[j]]-c+0.5)
   }
 
 return(as.dist(B))  
}

merge.geneset<-function(geneset)
{B=overlapdist(geneset)
hc<-hclust(B,'ave')
num=sum(diff(sort(hc$height),2)>1)+1
memb<-cutree(hc,num)

  len=nlevels(as.factor(memb))
 CC=list(pathway=NULL,gene=NULL,len=NULL)
 for (i in 1:len)
 {ind=which(memb==i)
  if (length(ind)>1)
  {CC$pathway[i]=paste(geneset$pathway[ind],collapse='|')
   CC$gene[[i]]=unique(as.numeric(unlist(geneset$gene[ind])))
  }else
  {  CC$pathway[i]=geneset$pathway[ind]
     CC$gene[[i]]=unique(as.numeric(geneset$gene[[ind]]))
  }
  CC$len[i]=length(CC$gene[[i]])
 }
CC
}

DEpath<-function(geneset,path,n.unique.allgenes,sym.base,FDR=F)
{ whatgene=geneset[,'humanEntrez']
  nwhatgene=length(whatgene)
  #intersect.gene=colSums(sym.base[intersect(whatgene,rownames(sym.base)),])  # intersection of pathway and source set
  ccc<-table(unlist(sym.base[as.character(intersect(whatgene,names(sym.base)))]))
  intersect.gene<-matrix(0,1,length(path$pathway))
  intersect.gene[as.numeric(names(ccc))]=ccc
  hyperg=phyper(intersect.gene-1,nwhatgene,n.unique.allgenes-nwhatgene,path$len,lower.tail=FALSE) #hypergeometric test
  
  if (FDR==T)  hyperg=p.adjust(hyperg,"fdr")  #FDR correction using BH method
  gene=lapply(path$gene[hyperg<0.05],function(x) intersect(x,whatgene))
  res=merge.geneset(list(pathway=path$pathway[hyperg<.05],gene=gene,len=intersect.gene[hyperg<.05]))
  res$symbol=lapply(res$gene,function(x) as.character(geneset[geneset[,'humanEntrez'] %in% x,'Symbol']))
  res
}
###################################################################
#Description: Load gene sets
#Usage: readpathway(pathway.base)
#Argument:pathway.base: pathway database
#Value: genes and length of each gene set
#Details: Load gene sets from local database
#Note: 
#Authors: Ruoting Yang
#Last update 09/23/2014
###################################################################

readpathway=function(pathway.base)
{  path.gene=lapply(pathway.base[,'HumanEntrezID'],function(x)read.table(text=as.character(x),sep='|'))
   names(path.gene)=pathway.base[,'ID']
   pathlen=unlist(lapply(path.gene,length)) # All path lengths 
   return(list(gene=path.gene,len=pathlen))
}

create_module<-function(geneset,species='human',type='Entrez',FDR=T)
{
  mainpath='r/'
  geneset<-as.matrix(geneset)
  geneset<-geneset[!duplicated(geneset),,drop=F]
if (type=='Entrez' & !is.null(geneset))  if (class(geneset[1])!='integer' & class(geneset[1])!='numeric')  stop('Entrez ID is unrecognizable. ')
if (type=='Symbol' & !is.null(geneset))  if (class(geneset[1])!='character' & class(geneset[1])!='factor')  stop('Symbol ID is unrecognizable. ')
                                                                                                       
switch(species,
         'rat' = {tryCatch(load(paste(mainpath,"rat.RData",sep='')),error={return})  
                  entrezsize<-sum(!is.na(geneid_to_human[,1]))},
         'mouse' = {tryCatch(load(paste(mainpath,"mouse.RData",sep='')),error={return})  
                    entrezsize<-sum(!is.na(geneid_to_human[,1]))},
         'monkey' = {tryCatch(load(paste(mainpath,"monkey.RData",sep='')),error={return})  
                     entrezsize<-sum(!is.na(geneid_to_human[,1]))},
         'human' =   {tryCatch(load(paste(mainpath,"human.RData",sep='')),error={return})  
                      entrezsize<-27958}
  )                      

print(paste('Identify',length(geneset),'human analogue',type,sep<-' '))
geneset<-data.frame(geneset)
names(geneset)<-type

  if (!is.null(geneset$Entrez))
  { geneset$Symbol<-geneid_sym[as.character(geneset$Entrez),'Gene_Symbol']
  }else
  {geneid_sym<-na.omit(geneid_sym)
   temp<-geneid_sym[,1]
   geneid_sym[,1]<-rownames(geneid_sym)
   rownames(geneid_sym)<-temp
   geneset$Entrez<-geneid_sym[as.character(geneset[,"Symbol"]),'Gene_Symbol']
  }
  if (species!='human')  {geneset$humanEntrez = geneid_to_human[as.character(geneset$Entrez),]
  }else geneset$humanEntrez <- geneset$Entrez
  if (nrow(na.omit(geneset))==0) geneset=NULL

geneset=geneset[!is.na(geneset[,'humanEntrez']),]
res=DEpath(geneset,path,entrezsize,sym.base,FDR=FDR)
res$geneset=geneset
return(res)
}

#load('D:/FACT/PPI.RData')
#string=read.table('D:/database_resource/PPI/string.txt',header=T,sep='\t')
#levels(string[,1])=PPI[levels(string[,1]),]
#levels(string[,2])=PPI[levels(string[,2]),]
#string=na.omit(string[,1:4])
#save(string,file='D:/FACT/string.RData')

get.interaction<-function(gene,file)
{
all=lapply(gene$gene,function(x) which((string[,1] %in% x & string[,2] %in% x & string[,4]>=0.4)==1))
res=string[unique(unlist(all)),1:2]
x=gene$geneset
rownames(x)=x[,'humanEntrez']
res[,1]=x[as.character(res[,1]),1]
res[,2]=x[as.character(res[,2]),1]

colnames(res)=c('sourceId','targetId')
if (length(file)!=0)
write.table(res,file,sep='\t',col.names=T,row.names=F,quote=F)
else
res  
}


get.path<-function(res,group,file)
{result=NULL
for (i in 1:length(res$symbol))
{temp=cbind(res$symbol[[i]],rep(res$pathway[[i]],length(res$symbol[[i]]),1))
result=rbind(result,temp)
}
rownames(group)=group[,'id']
if (ncol(group)==2)
{result=cbind(result,as.character(group[result[,1],'group']))}
else
{result=cbind(result,rep('',1,length(result[,1])))}

colnames(result)=c('id','type','group')
if (length(file)!=0)
  write.table(result,file,sep='\t',col.names=T,row.names=F,quote=F)
else
  result  
}

group=read.table('data/tempnodes.txt',header=T,sep='\t')

load('r/humanpathway.merge.RData')
load('r/string.RData')

res=create_module(group[,'id'],species='human',type='Symbol',FDR=T)
get.interaction(res,'data/templinks.txt')
get.path(res,group,'data/tempnodes.txt')
